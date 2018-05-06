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
}
