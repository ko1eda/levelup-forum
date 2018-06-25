<?php

namespace App\Inspections;

use App\Inspections\Spam;
use App\Inspections\InvalidKeywords;
use App\Inspections\RepeatedCharacters;

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
    protected $inspections = [];


    /**
     * Takes an optional indexed array of additional keywords
     * and an optional threshold parameter for testing
     *
     * @param array $blacklist
     * @param int $threshold
     * @return void
     */
    public function __construct(
        InvalidKeywords $invalidKeyWords,
        RepeatedCharacters $RepeatedCharacters
    ) {
        $this->inspections = func_get_args();
    }


    /**
     * Loop through the list of Spam Inspection classes
     * Resolve them from the IoC containter
     * Call their scan method on the passed in text
     * Return null if no exception is thrown
     *
     * @param String $message
     * @return Exception|boolean
     */
    public function detect(String $message)
    {
        foreach ($this->inspections as $inspection) {
            $inspection->scan($message);
        }

        return false;
    }
}
