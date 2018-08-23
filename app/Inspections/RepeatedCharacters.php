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
        // match any non space or non-newline character
        $regExFull = '/([^\s\n])\1{15,}/mi';

        $this->numHits += preg_match_all($regExFull, $message);

        $this->checkSpamStatus();

        return false;
    }
}
