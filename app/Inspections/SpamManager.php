<?php

namespace App\Inspections;

class SpamManager
{

    /**
     * $threshold
     *
     * @var integer
     */
    protected $threshold;

    
    /**
     * $blacklist
     *
     * @var array
     */
    protected $blacklist;


    /**
     * List of spam inspections to run
     *
     * @var array
     */
    protected $inspections = [
        InvalidKeywords::class,
        RepeatedCharacters::class
    ];


    /**
     * Takes an optional indexed array of additional keywords
     * and an optional threshold parameter for testing
     *
     * @param array $blacklist
     * @param int $threshold
     * @return void
     */
    public function __construct(array $blacklist = null, int $threshold = null)
    {
        !$blacklist ? : $this->blacklist = $blacklist;
        !$threshold ? : $this->threshold = $threshold;
    }


    /**
     * If no exception is thrown by the tests
     * then return false
     *
     * @param String $message
     * @return mixed
     */
    public function detect(String $message)
    {
        foreach ($this->inspections as $inspection) {
            app()->makeWith($inspection, [$this->threshold, $this->blacklist])
                ->scan($message);
        }
        
        return false;
    }
}
