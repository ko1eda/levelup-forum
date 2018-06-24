<?php

namespace App\Inspections;

use App\Inspections\Spam;
use App\Inspections\Contracts\SpamDetectionInterface;

class InvalidKeywords extends Spam
{

    /**
     * An array of keywords or terms
     * to run through the spam detector.
     *
     * @var array
     */
    protected $blacklist = [
        'Yahoo Customer Support'
    ];


    /**
     * Takes an optional indexed array of additional keywords
     * and an optional threshold parameter for testing
     *
     * @param array $blacklist
     * @return void
     */
    public function __construct(int $threshold = null, array $blacklist = null)
    {
        // If there is a $blacklist merge it with the existing
        !$blacklist ?: array_merge($this->blacklist, $blacklist);

        !$threshold ?: $this->threshold = $threshold;
    }


    /**
     * If no exception is thrown by the test
     * then return false
     *
     * @param String $message
     * @return boolean
     */
    public function scan(String $message)
    {
        foreach ($this->blacklist as $keyword) {
            $pattern = $this->buildRegEx($keyword);

            $this->numHits .= preg_match_all($pattern, $message);
    
            $this->checkSpamStatus();
        }
        
        return false;
    }

   
    /**
     * Split the keyword into its individial words
     * loop through the words and build a regexpression from them
     * then return the expression
     *
     * The expression checks for any character
     * That could seperate the phrase
     *
     * @param String $message
     * @return String
     */
    protected function buildRegEx(String $keyword)
    {
        $reg = '(?:[^\w]+)?';
        $regExFull = '/(?:[^\w]+)?';
        $keywords = preg_split('/[\W]+/m', $keyword);
        $len = count($keywords);

        // If the keyword is only one word
        if ($len == 1) {
            return $regExFull . $keywords[0] . $reg . '/i';
        }

        // If the keyword is a phrase
        for ($i = 0; $i < $len; $i++) {
            trim($keywords[$i]);
        
            // the end of the array
            if ($i === $len - 1) {
                $regExFull .= $keywords[$i] . $reg . '/i';
                break;
            }

            $regExFull .= $keywords[$i] . $reg;
        }

        return $regExFull;
    }
}
