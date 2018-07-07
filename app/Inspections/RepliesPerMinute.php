<?php

namespace App\Inspections;

use App\Inspections\Spam;

class RepliesPerMinute extends Spam
{

    protected $timeInMinutesBetweenReplies;

    /**
     * If no exception is thrown by the tests
     * then return false
     *
     * @param String $message
     * @return boolean
     */
    public function scan(String $message)
    {
        $spamDetected =  \Auth::user()->detectReplyWithin($timeInMinutesBetweenReplies);

        if ($spamDetected) {
            $this->numHits = $this->threshold;
            $this->checkSpamStatus();
        }
        
        return false;
    }
}
