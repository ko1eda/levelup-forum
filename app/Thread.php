<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\RecordActivity;
use App\Traits\SubscribableTrait;
use App\Interfaces\SubscribableInterface;
use App\Events\ReplyPosted;
use App\Traits\RecordViews;
use App\Widgets\Trending;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Redis;
use Laravel\Scout\Searchable;

class Thread extends Model implements SubscribableInterface
{

    use RecordActivity,
        RecordViews,
        SubscribableTrait,
        Searchable;

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
     * $casts
     *
     * @var array
     */
    protected $casts = ['locked' => 'boolean'];


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
     * clean any html before the thread is returned
     * by the application
     *
     * @param mixed $body
     * @return void
     */
    public function getBodyAttribute($body)
    {
        return \Purify::clean($body);
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
     * Set threads best_reply_id to $reply
     *
     * Returns a key to be used to cache the reply
     *
     * @return String $key
     */
    public function markBestReply(int $id)
    {
        $this->best_reply_id = $id;

        $this->save();

        $key = 'thread:' . $this->id;

        return $key;
    }


    /**
     * return the best reply for the thread from redis
     *
     * @return void
     */
    public function bestReply()
    {
        $key = 'thread:' . $this->id;

        if ($item = Redis::hget($key, 'best_reply')) {
            return  unserialize($item);
        }
        // return $this->replies()->where('id', $this->best_reply_id)->first();
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
