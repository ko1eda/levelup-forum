<?php

namespace App\POPO;

use Illuminate\Support\Facades\Redis;

/**
 * ------------------------
 * Stats Tracker
 * ------------------------
 *
 * This object can be used to track
 * the metric of an entity in the application.
 *
 * It is a basic wrapper for redis
 * that uses redis to store and retieve basic metrics
 *
 */

class StatsTracker
{
    protected $store;

    protected $entity;

    protected $field;

    protected $cacheKey;

    protected $testing = false;


    /**
     * __construct
     *
     * @param mixed $entity the object whose views you want to track
     * @param mixed $field the field you want to store in the hashmap
     * @return void
     */
    public function __construct($entity, $field)
    {
        $this->store = new Redis;

        $this->entity = $entity;

        $this->field = $field;

        $this->testing = $this->checkTesting();

        $this->setCacheKey();
    }


    /**
     * return a newly configfured instance of the class
     *
     * @param mixed $entity
     * @param mixed $field
     * @return void
     */
    public static function track($entity, $field)
    {
        return new static($entity, $field);
    }


    /**
     * increment the member in the hashmap by a value
     *
     * @return void
     */
    public function increment($value = 1)
    {
        $this->store::hincrby($this->cacheKey, $this->field, $value);

        if ($this->testing) {
            $this->store::expire($this->cacheKey, 180);
        }
    }


    /**
     * store and encode the value you would like to track in the hashmap
     *
     * @return void
     */
    public function put($value)
    {
        $this->store::hset($this->cacheKey, $this->field, json_encode($value));

        // If testing expire any testing cache in 3 minutes
        if ($this->testing) {
            $this->store::expire($this->cacheKey, 180);
        }
    }


    /**
     * return the count for numeric fields
     *
     * @return int
     */
    public function count() : int
    {
        return $this->store::hget($this->cacheKey, $this->field) ?? 0;
    }


    /**
     * clear the cache for the current $cacheKey
     *
     * @return void
     */
    public function clear()
    {
        $this->store::del($this->cacheKey);
    }


    /**
     * check to see if the environment is testing
     *
     * @return void
     */
    protected function checkTesting()
    {
        if (app()->environment('testing')) {
            return true;
        }

        return false;
    }


    /**
     * Sets the cache key for testing and non-testing env
     *
     * @return void
     */
    protected function setCacheKey()
    {
        // get the last entity from the class path aka the class name
        $class = explode('\\', strtolower(get_class($this->entity)));

        $class = end($class);

        if ($this->testing) {
            $this->cacheKey = 'test-' . $class . ':' . $this->entity->id ;
            
            return;
        }
        
        $this->cacheKey = $class . ':' . $this->entity->id ;
    }
}
