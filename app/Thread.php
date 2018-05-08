<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['body', 'title', 'user_id', 'channel_id'];

    /**
     * A thread can have many replies
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
     * Return a collection of threads
     * in DESC order where user.name equal
     * the $name passed from a query string
     *
     * @param String $name
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function filterByUser(String $name)
    {
        return static::whereHas(
            'user',
            function ($query) use ($name) {
                $query->where('name', '=', $name);
            }
        )->latest()->get();
    }
}
