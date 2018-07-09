<?php

namespace App\Interfaces;

interface SubscribableInterface
{
    /**
     * A model can have many subscriptions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function subscriptions();
    

    /**
     * Any class that adheres must be able
     * to add a subscription.
     *
     * @param int $userID
     * @return static
     */
    public function addSubscription(int $userID);


    /**
     * Any class that adheres must be able to notify
     * subscribers.
     *
     * @param \App\Interface\NotificationInterface $notification
     * @param array $blacklist list of user ids whom you do not want to notify
     * @return static
     */
    public function notifySubscribers(NotificationInterface $notification, array $blacklist);
   
   
    /**
     * Any class that adheres must be able to
     * remove a subscription.
     *
     * @param int $userID
     * @return static
     */
    public function removeSubscription(int $userID);
}
