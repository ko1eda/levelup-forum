<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    /**
     * Change the key that laravel uses
     * for route model binding
     * @return String
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * A channel has many threads
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function threads()
    {
        return $this->hasMany(Thread::class);
    }
}
