<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    /**
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
     * return path to current thread
     * @return String
     */
    public function path(String $append = '')
    {
        return '/threads/' .$this->id. $append;
    }

    /**
     * Add a reply to the given thread
     * @return void
     */
    public function addReply(array $reply)
    {
        $this->replies()->create($reply);
    }
}
