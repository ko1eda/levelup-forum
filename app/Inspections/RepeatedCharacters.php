<?php

namespace App\Inspections;

use App\Inspections\Spam;
use App\Inspections\Contracts\SpamDetectionInterface;

class RepeatedCharacters extends Spam
{
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

        $this->numHits += preg_match_all($regExFull, $message);

        $this->checkSpamStatus();

        return false;
    }
}
