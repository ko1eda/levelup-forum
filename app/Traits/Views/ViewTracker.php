<?php

namespace App\Traits\Views;

use Illuminate\Support\Facades\Redis;

class ViewTracker
{
    protected $store;

    protected $item;

    protected $cacheKey;


    /**
     * __construct
     *
     * @param Redis $redis
     * @param mixed $item the object whose views you want to track
     * @return void
     */
    public function __construct(Redis $redis, $item)
    {
        // If testing make testing keys
        $this->store = $redis;

        $this->item = $item;

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
    }


    /**
     * return view count
     *
     * @return int
     */
    public function count() :int
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
     * Sets the cache key for testing and non-testing env
     *
     * @return void
     */
    protected function setCacheKey()
    {
        if (app()->environment('testing')) {
            $this->cacheKey = 'test-' . get_class($this->item) . ':' . $this->item->id . ':views';
        } else {
            $this->cacheKey = get_class($this->item) . ':' . $this->item->id . ':views';
        }
    }
}
