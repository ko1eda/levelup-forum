<?php

namespace App\Traits;

use App\Favorite;

trait Favoritable
{

    /**
     * An entity can have many favorites
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
     * firstOrCreate ensures if a favorite already exists in the db
     * its user_id corresponds to the logged user
     * that user cannot create the favorite (aka favorite the entity) twice
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
    public function isFavorited()
    {
        if (!\Auth::check()) {
            return false;
        }

        return $this->favorites->where('user_id', \Auth::user()->id)->count();
    }

}