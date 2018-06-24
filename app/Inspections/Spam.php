<?php

namespace App\Inspections;

abstract class Spam
{
    /**
     * The number of matches
     * the a spam filter returns
     *
     * @var integer
     */
    protected $numHits = 0;


    /**
     * The max number of acceptable
     * hits before an error is thrown
     *
     * @var integer
     */
    protected $threshold = 1;


    /**
     * scan
     *
     * @param String $message
     * @return void
     */
    abstract public function scan(String $message);



    /**
     * Determine if the number of spam hits
     * is over the given threshold
     *
     * @return Exception|boolean
     */
    protected function checkSpamStatus()
    {
        if ($this->numHits >= $this->threshold) {
            throw new \Exception('Spam Detected Exception');
        }

        return false;
    }
}
