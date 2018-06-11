<?php

namespace App\Traits;

use App\Subscription;
use App\User;

trait SubscribableTrait
{

    /**
     * Delete all favorites associated with
     * Any model who uses Subscribable trait
     *
     * Note it deletes all subscriptions individually
     * so that it will trigger the subscriptions delete
     * method
     *
     * @return void
     */
    protected static function bootSubscribableTrait()
    {
        static::deleting(function ($model) {
            $model->subscriptions->each(function ($subscription) {
                $subscription->delete();
            });
        });
    }

    /**
     * A model that uses this trait can have many subscriptions.
     *
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function subscriptions()
    {
        return $this->morphMany(Subscription::class, 'subscribable');
    }


    /**
     * Store an entry corresponding to the
     * given thread and authenticated user to
     * the subscriptions table
     *
     * @param User $user
     * @return void
     */
    public function addSubscription(User $user = null)
    {
        $this->subscriptions()->firstOrCreate([
            'user_id' => $user ? $user->id : \Auth::user()->id
        ]);
    }
   
   
    /**
     * removeSubscription
     *
     * @param User $user
     * @return void
     */
    public function removeSubscription(User $user = null)
    {
        $this->subscriptions()
            ->where('user_id', $user ? $user->id : \Auth::user()->id)
            ->delete();
    }
}
