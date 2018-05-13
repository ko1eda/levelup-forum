<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Reply extends Model
{
    protected $fillable = ['user_id', 'body'];

    protected $withCount=['favorites'];

     /**
     * boot
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(function (Builder $builder) {
            $builder->with('favorites', 'user');
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

    /**
     * A reply can have many favorites,
     * However other models may also use this favorites
     * table
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    /**
     * Persist a favorite to the database
     * the favoirtable id, and favoritable type
     * are provided automatically by laravel
     *
     * firstOrCreate ensures if a reply exists in the db
     * for a given user than that user cannot
     * favorite the same reply multiple times
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function addFavorite()
    {
        return $this->favorites()
            ->firstOrCreate(['user_id' => \Auth::user()->id]);
    }

    /**
     * First Check if the user is authenticated
     * if so, check to see if the
     * user had already favorited the reply count(1) true
     * count(0) false 
     *
     * NOTE THIS WHERE METHOD IS ON THE COLLECTION CLASS
     * SINCE WE EAGER LOAD USER in the boot method
     * we can then access user_id as a property on the model
     * 
     * @return void
     */
    public function wasFavorited()
    {
        if (! \Auth::check()) {
            return false;
        }

        return $this->favorites->where('user_id', \Auth::user()->id)->count();
    }
}