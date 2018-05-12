<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $fillable = ['user_id', 'body'];

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
     * user had already favorited the reply
     *
     * @return void
     */
    public function wasFavorited()
    {
        if (! \Auth::check()) {
            return false;
        }

        return $this->favorites()
            ->where('user_id', \Auth::user()->id)
            ->exists();
    }
}