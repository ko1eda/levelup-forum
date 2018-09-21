<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    /**
     * $fillable
     *
     * @var array
     */
    protected $fillable = ['user_id', 'type', 'value'];
}
