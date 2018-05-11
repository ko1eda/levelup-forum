<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = ['favoritable_id', 'favoritable_type', 'user_id'];

    /**
     * reply
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function favoritable()
    {
        return $this->morphTo();
    }
}
