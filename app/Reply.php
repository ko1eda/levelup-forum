<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\Favoritable;
use App\Traits\RecordActivity;

class Reply extends Model
{
    use Favoritable, RecordActivity;
    
    protected $fillable = ['user_id', 'body'];
    protected $withCount=['favorites'];

    // this adds any custom properties to 
    // the json serializiation of the object
    protected $appends = ['is_favorited'];

     /**
     * boot
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('favorites', function (Builder $builder) {
            $builder->with('favorites');
        });

        static::addGlobalScope('user', function (Builder $builder) {
            $builder->with('user');
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}