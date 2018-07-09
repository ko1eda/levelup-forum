<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\RecordActivity;
use App\Traits\SubscribableTrait;
use App\Notifications\ThreadUpdated;
use App\Interfaces\SubscribableInterface;

class Thread extends Model implements SubscribableInterface
{

    use RecordActivity, SubscribableTrait;

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

        // These are called model events

        // When a thread is delete also delete its
        // replies
        // Note:
        // you have to use an each on the replies collection because
        // if you just delete the threads->replies()->delete()
        // then it will not trigger the deleted event
        // on the reply model which intern means
        // that replies favorites will not be deleted
        // by the favoritable trait and its activites
        // will not be deleted
        static::deleting(function ($thread) {
            $thread->replies->each(function ($reply) {
                $reply->delete();
            });
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    /**
     * Return path to current thread
     * @param String $append - any additional path
     * @return String
     */
    public function path(String $append = '')
    {
        return (
            "/threads/{$this->channel->slug}/{$this->id}". $append
        );
    }

    /**
     * Add a reply to the given thread
     * and notify the subscribed users
     * 
     * @return App\Reply $reply
     */
    public function addReply(array $reply)
    {
        $reply = $this->replies()->create($reply);
        
        $this->notifySubscribers(
            new ThreadUpdated($this, $reply),
            [$reply->user_id]
        );

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
