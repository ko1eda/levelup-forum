<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{

    protected $fillable = ['user_id'];

    
    public function subscribable()
    {
        return $this->morphTo('subscribable');
    }
}
