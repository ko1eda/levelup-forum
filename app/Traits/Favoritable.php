<?php

namespace App\Traits;

use App\Favorite;

trait Favoritable
{

    /**
     * Delete all favorites associated with
     * Any model who uses Favoritable trait
     *
     * Note it deletes all favorites individually
     * so that it will trigger the favorites delete
     * method -- this is so any Activity related
     * to a favorite will also be deleted
     *
     * @return void
     */
    protected static function bootFavoritable()
    {
        static::deleting(function ($model) {
            $model->favorites->each(function ($favorite) {
                $favorite->delete();
            });
        });
    }


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
     * Remove a favorite from the database if that favorite
     * exists for the given user
     *
     * If not fail with a status code 404 not found (firstOrFail)
     *
     * @return void
     */
    public function removeFavorite()
    {
        return $this->favorites()
            ->where('user_id', \Auth::user()->id)
            ->firstOrFail()
            ->delete();
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
     * @return boolean
     */
    public function isFavorited()
    {
        if (!\Auth::check()) {
            return false;
        }

        return (boolean) $this->favorites->where('user_id', \Auth::user()->id)->count();
    }


    /**
     * Adds an is_favorited
     * attribute to the serilization of any class
     * that uses this trait (see -Reply.php for example)
     *
     * What this means is that any model that subscribes to this trait
     * will have an is_favorited attribute on their toJSON or toArray serializations
     * This attribute will reflect the returned boolean from the isFavorited method above 
     * https://laravel.com/docs/5.6/eloquent-serialization
     * 
     * @return boolean
     */
    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }
}
