<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\RecordActivity;
use App\Traits\SubscribableTrait;
use App\Interfaces\SubscribableInterface;
use App\Events\ReplyPosted;
use Illuminate\Support\Facades\Redis;
use App\Traits\Views\RecordViews;
use App\Widgets\Trending;
use Vinkla\Hashids\Facades\Hashids;

class Thread extends Model implements SubscribableInterface
{

    use RecordActivity,RecordViews, SubscribableTrait;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['body', 'title', 'user_id', 'channel_id'];
    
    /**
     * Add a count to the returned model.
     * @var array
     */
    protected $withCount = ['replies'];

    /**
     * Append mutators to the returned model.
     * @var array
     */
    protected $appends = ['is_subscribed'];


    /**
     * boot
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // Note global scopes need to be named,
        // In order to use withoutGlobalscope(s) function
        static::addGlobalScope('channel', function (Builder $builder) {
            $builder->with('channel');
        });

        static::addGlobalScope('user', function (Builder $builder) {
            $builder->with('user');
        });

        // give the thread a slug when it is being created
        static::creating(function ($thread) {
            $thread->slug = str_slug($thread->title);
        });
        
        static::deleting(function ($thread) {
            // Delete all replies associated with the
            $thread->replies->each(function ($reply) {
                $reply->delete();
            });

            // Clear the threads views from the redis cache
            $thread->views()->clear();

            // and remove the thread from the trending cache
            (new Trending(new Redis))->remove($thread);
        });
    }


    /**
     * A thread can have many replies.
     * Load the replies favoirtes and users as well
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    
    /**
     * A thread belongs to a user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * A thread belongs to a channel (main category)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }


    /**
     * Get the value of the model's route key.
     *
     * @return mixed
     */
    public function getRouteKey()
    {
        return Hashids::connection('threads')->encode($this->getKey());
    }


    /**
     * Add a reply to the given thread
     *
     *
     * @return App\Reply $reply
     */
    public function addReply(array $reply)
    {
        $reply = $this->replies()->create($reply);

        event(new ReplyPosted($this, $reply));

        return $reply;
    }


    /**
     * Call the apply method of
     * the ThreadFilters class
     * passing in an Instance <of></of>
     * the querybuilder
     *
     * @param mixed $query
     * @param mixed $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, $filters)
    {
 
        return $filters->apply($query);
    }
}
