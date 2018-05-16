<?php

namespace App\Traits;

use App\Activity;

trait RecordActivity
{

    protected static $events = ['created', 'deleted'];

    /**
     * Laravel will trigger anything
     * inside this boot method
     * as if it were in the boot
     * method of the models that use this trait
     *
     * So we loop through the whitelisted events
     * in the protected array and create a record
     * for each event if it occurs on any model
     * that uses this trait
     *
     * @return void
     */
    protected static function bootRecordActivity()
    {
        foreach (static::$events as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }
    }

    /**
     * activities
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activities()
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
            $this->activities()->create([
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