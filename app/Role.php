<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * a role can have many users associated with it
     *
     * ex
     * admin or superuser id: 1
     * moderator id: 2
     * standard user id: 3
     * @return void
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
