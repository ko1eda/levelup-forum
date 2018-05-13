<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Thread extends Model
{

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['body', 'title', 'user_id', 'channel_id'];


    /**
     * boot
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(function (Builder $builder) {
            $builder->with('channel', 'user')->withCount('replies');
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
     * @return void
     */
    public function addReply(array $reply)
    {
        $this->replies()->create($reply);
    }


    /**
     * Call the apply method of
     * the ThreadFilters class
     * passing in an Instance of
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
