<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }


    /**
     * return path to current thread
     * @return String
     */
    public function path()
    {
        return '/threads/' .$this->id;
    }
}
