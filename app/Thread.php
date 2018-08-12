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
use Carbon\Carbon;

class Thread extends Model implements SubscribableInterface
{

    use RecordActivity,RecordViews, SubscribableTrait;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['body', 'title', 'user_id', 'channel_id', 'slug'];
    
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


        static::created(function ($thread) {
            // $sinceEpoch = $thread->created_at->diffInSeconds(\Carbon\Carbon::createFromTimestamp(0));

            // $hash = $sinceEpoch + $thread->id;

            $thread->slug = Hashids::connection('threads')->encode($thread->id);

            $thread->save();
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
     * Return path to current thread
     *
     * @param String $append - any additional path
     * @return String
     */
    public function path(String $append = '')
    {
        return (
            "/threads/{$this->channel->slug}/{$this->slug}". $append
        );
    }


    /**
     * getRouteKeyName
     *
     * @return void
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }


    // /**
    //  * sets the passed in thread id to a hashid slug
    //  *
    //  * @param mixed $slug
    //  * @param HashidsManager $hashids
    //  * @return void
    //  */
    // public function setSlugAttribute($value)
    // {
    //     // gets the last id stored in the table
    //     $lastID = $this->getSlugIncrement();

    //     // increment the returned id by one
    //     ++ $lastID;

    //     // set the slug to the difference in from unix 00:00:00 until the threads creation
    //     // multiply it by 1000 to get miliseconds
    //     $timeSinceEpoch = Carbon::now()->diffInSeconds(Carbon::createFromTimestamp(0)) * 1000;

    //     // pick a random value from the time in seconds since unix 0 plus the incremented ID
    //     $hash = random_int(0, $timeSinceEpoch) + $lastID;

    //     // encode the incremeneted and store it in the db
    //     $this->attributes['slug'] = Hashids::connection('threads')->encode($hash);
    // }


    // /**
    //  * getSlugIncrement
    //  *
    //  * @param int $value
    //  * @return void
    //  */
    // protected function getSlugIncrement() : int
    // {
    //     // get the largest id in the table (the most recent id)
    //     $result = Thread::max('id');

    //     // if the result is null return 0 bc it is the first thread in the table
    //     if ($result === null) {
    //         return 0;
    //     }

    //     // return the result
    //     return $result;
    // }


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
