<?php 

namespace App\Widgets;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Carbon;
use App\Thread;

class Trending
{
    /**
     * $cache
     *
     * @var undefined
     */
    protected $cache;


    /**
     * $cacheKey
     *
     * @var string
     */
    protected $cacheKey;


    /**
     * $withScores
     *
     * @var boolean
     */
    protected $withScores;



    public function __construct(Redis $cache)
    {
        $this->cache = $cache;

        $this->cacheKey = app()->environment('testing') ? 'testing_trending_threads' : 'trending_threads';

        $this->withScores = false;
    }


    public function store(Thread $thread)
    {
        $this->cache::zincrby($this->cacheKey, 1, json_encode([
            'title' => $thread->title,
            'uri' => route('threads.show', [$thread->channel, $thread], false),
            'username' => $thread->user->username,
            'replies_count' => $thread->replies_count,
        ]));

        return $this;
    }



    /**
     * Returns the specified range from the trending threads
     *
     * @param mixed array
     * @return void
     */
    public function get(array $range = [])
    {
        $minRange = $range[0] ?? 0;

        $maxRange = $range[1] ?? -1;

        if ($this->withScores) {
            return $this->listWithScores($minRange, $maxRange);
        }


        return $this->list($minRange, $maxRange);
    }


    
    /**
     * If you want your trending items with their associated score
     *
     * @return void
     */
    public function withScores()
    {
        $this->withScores = true;

        return $this;
    }



    /**
     * return a decoded array of trendings items
     *
     * @param mixed array
     * @return array
     */
    protected function list(int $min, int $max)
    {
        $json = $this->getJsonArrayDesc($min, $max);

        return array_map('json_decode', $json);
    }



    /**
     * Gets an array of trending threads
     * with an associated views_count property
     *
     * @param mixed array
     * @return array
     */
    protected function listWithScores(int $min, int $max)
    {
        // $minRange = $range[0] ?? 0;

        // $maxRange = $range[1] ?? -1;

        $json = $this->getJsonArrayDesc($min, $max);

        // Note that because of the way predis outputs the withscores
        // in the form json => score where the data is the key and the score is the value
        // we need to decode the key, and then add the value (score) the decoded json object
        return Collect($json)->map(function ($item, $key) {
            $decodedThread = json_decode($key);

            $decodedThread->view_count = $item;

            return $decodedThread;
        })
        ->values()
        ->toArray();
    }

 

    /**
     * gets the encoded array of json items
     *
     * @return array
     */
    protected function getJsonArrayDesc($minRange, $maxRange)
    {
        if ($this->withScores) {
            return $this->cache::zrevrange($this->cacheKey, $minRange, $maxRange, 'WITHSCORES');
        }
        
        return $this->cache::zrevrange($this->cacheKey, $minRange, $maxRange);
    }


    /**
     * withExpire
     *
     * @param int $timeInMinutes
     * @return void
     */
    public function withExpire(int $timeInMinutes)
    {
        if ($this->cache::ttl($this->cacheKey) === -1) {
            $this->cache::expire($this->cacheKey, floor($timeInMinutes * 60));
        }
    }


    /**
     * withExpireHours
     *
     * @param int $timeInHours
     * @return void
     */
    public function withExpireHours(int $timeInHours)
    {
        // only add and expire if one hasn't been set yet
        if ($this->cache::ttl($this->cacheKey) === -1) {
            $this->cache::expire($this->cacheKey, Carbon::now()->addHours($timeInHours)->diffInSeconds());
        }
    }


  


    /**
     * deletes a given set
     *
     * @param mixed $key
     * @return bool
     */
    public function flush($key = null)
    {
        $this->cache::del($key ?? $this->cacheKey);

        return true;
    }


    // public function __call($method, $arguments)
    // {
    //     if ($method === 'listWithScores') {
    //         return $this->$method(...$arguments);
    //     }
    // }


    // public static function __callStatic($method, $arguments)
    // {
    //     if (method_exists(Trending::class, $method)) {
    //         $redis = new Redis;
    //         return (new static($redis))->$method(...$arguments);
    //     }
    // }
}
