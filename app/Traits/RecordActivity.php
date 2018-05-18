<?php

namespace App\Traits;

use App\Activity;

trait RecordActivity
{

    protected static $events = ['created'];

    /**
     * Laravel will trigger anything
     * inside this boot method
     * as if it were in the boot
     * method of the models that use this trait
     *
     * @return void
     */
    protected static function bootRecordActivity()
    {
        // Loop through the whitelisted events
        // in the protected array and create a record
        // for each event if it occurs on any model
        // that uses this trait
        foreach (static::$events as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }

        // Delete all recorded activity for any
        // subscribed model
        static::deleting(function ($model) {
            $model->activity()->delete();
        });
    }

    /**
     * Note this is a polymorphic one-to-many relationship
     * although i denoted it as activity.
     * I did this because user has a regular one-to-many
     * relationship with the Activity table on its user_id column
     *
     * So a user can have many activities,
     * and each Model that uses this trait can have many activties
     * however you can think of it as the model creates theses activity
     * singular.
     * And a user owns those created activities, plural.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    /**
     * Log the activity
     * of the currently signed in user
     * for their activity feed
     *
     * @param String $event ex: updated, created, deleted..
     * @return void
     */
    protected function recordActivity(String $event)
    {
        if (\Auth::check()) {
            $this->activity()->create([
                'type' => $this->getActivityType($event),
                'user_id' => \Auth::user()->id
            ]);
        }
    }

    /**
     * Get the class path then split it into an
     * array. Get the last value in that array
     * i.e. the class name, then convert it to lowercase
     * and concatonated with the event type.
     * ex: created_thread, or updated_reply
     *
     * @param String $event
     * @return String
     */
    protected function getActivityType(String $event) : String
    {
        $class = explode('\\', get_class($this));

        $class = strtolower(end($class));
        
        return $event . '_' . $class;
    }
}