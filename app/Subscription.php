<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{

    protected $fillable = ['user_id'];

    /**
     * A subscription can belong to different models
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function subscribable()
    {
        return $this->morphTo('subscribable');
    }

    /**
     * A subscription is assocaited with a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
