<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordActivity;

class Favorite extends Model
{
    use RecordActivity;
    
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
