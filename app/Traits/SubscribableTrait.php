<?php

namespace App\Traits;

use App\Subscription;
use App\User;
use App\Interfaces\NotificationInterface;

trait SubscribableTrait
{

    /**
     * Delete all subscriptions associated with
     * any model who uses Subscribable trait
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
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function subscriptions()
    {
        return $this->morphMany(Subscription::class, 'subscribable');
    }


    /**
     * Store an entry corresponding to the
     * given model and authenticated user to
     * the subscriptions table
     *
     * @param User $user
     * @return \App\Interfaces\SubscribableInterface $this
     */
    public function addSubscription(int $userID = null)
    {
        $this->subscriptions()->firstOrCreate([
            'user_id' => $userID ? $userID : \Auth::user()->id
        ]);

        return $this;
    }


    /**
     * For each subscription on the given model,
     * find all the associated subscribed users whose user ids are not in
     * the blacklist and send them the passed in notification
     *
     * @param \App\Interface\NotificationInterface $notification
     * @param array $blacklist list of user ids whom you do not want to notify
     * @return \App\Interfaces\SubscribableInterface $this
     */
    public function notifySubscribers(NotificationInterface $notification, array $blacklist = [-1])
    {
        $this->subscriptions
            ->whereNotIn('user_id', $blacklist)
            ->each(function ($subscription) use ($notification) {
                $subscription->user->notify($notification);
            });

        return $this;
    }
   
   
    /**
     * removeSubscription
     *
     * @param User $user
     * @return \App\Interfaces\SubscribableInterface $this
     */
    public function removeSubscription(int $userID = null)
    {
        $this->subscriptions()
            ->where('user_id', $userID ? $userID : \Auth::user()->id)
            ->delete();
            
        return $this;
    }

    /**
     * getIsSubscribedAttribute
     *
     * @return void
     */
    public function getIsSubscribedAttribute()
    {
        if (!\Auth::check()) {
            return false;
        }
        
        return $this->subscriptions()
            ->where('user_id', \Auth::user()->id)
            ->exists();
    }
}
