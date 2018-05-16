<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $guarded = [];


    /**
     * subject
     *
     * @return void
     */
    public function subject()
    {
        return $this->morphTo();
    }
}
