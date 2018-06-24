<?php

namespace App\Inspections;

use App\Inspections\Spam;
use App\Inspections\Contracts\SpamDetectionInterface;

class RepeatedCharacters extends Spam
{

    /**
     * Takes an optional indexed array of additional keywords
     * and an optional threshold parameter for testing
     *
     * @param int $threshold
     * @return void
     */
    public function __construct(int $threshold = null, array $blacklist = null)
    {
        !$threshold ?: $this->threshold = $threshold;
    }


    /**
     * If no exception is thrown by the tests
     * then return false
     *
     * @param String $message
     * @return boolean
     */
    public function scan(String $message)
    {
        $regExFull = '/(.)\1{9,}/mi';

        $this->numHits .= preg_match_all($regExFull, $message);

        $this->checkSpamStatus();

        return false;
    }
}
