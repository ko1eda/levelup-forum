<?php

namespace App\Traits\Views;

use Illuminate\Support\Facades\Redis;

class ViewTracker
{
    protected $store;

    protected $item;

    protected $cacheKey;

    protected $testing = false;


    /**
     * __construct
     *
     * @param Redis $redis
     * @param mixed $item the object whose views you want to track
     * @return void
     */
    public function __construct(Redis $redis, $item)
    {
        $this->store = $redis;

        $this->item = $item;

        $this->testing = $this->checkTesting();

        $this->setCacheKey();
    }


    /**
     * Increment view count
     *
     * @return void
     */
    public function record()
    {
        $this->store::incrby($this->cacheKey, 1);

        // If testing expire any testing cache in 3 minutes
        if ($this->testing) {
            $this->store::expire($this->cacheKey, 180);
        }
    }


    /**
     * return view count
     *
     * @return int
     */
    public function count() : int
    {
        return $this->store::get($this->cacheKey) ?? 0;
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
        if ($this->testing) {
            $this->cacheKey = 'test-' . get_class($this->item) . ':' . $this->item->id . ':views';
            
            return;
        }
        
        $this->cacheKey = get_class($this->item) . ':' . $this->item->id . ':views';
    }
}
