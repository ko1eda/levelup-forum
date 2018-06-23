<?php

namespace App;

class SpamFilter
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
     * to run against the spam detector
     *
     * @param array $blacklist
     * @return void
     */
    public function __construct(array $blacklist = null)
    {
        !$blacklist ? : array_merge($this->blacklist, $blacklist);
    }


    /**
     * detect
     *
     * @param String $message
     * @return mixed
     */
    public function detect(String $message)
    {
        if ($this->runAgainstBlackList($message)) {
            throw new \Exception('Spam Detected Exception');
        }

        return false;
    }


    /**
     * Runs the message against the
     * keyword $blacklist array
     *
     * @param String $message
     * @return boolean
     */
    protected function runAgainstBlacklist(String $message)
    {
        $numHits = 0;
    
        foreach ($this->blacklist as $keyword) {
            $pattern = $this->buildRegEx($keyword);

            $numHits .= preg_match_all($pattern, $message);
        }

        return $numHits >= 1 ? true : false;
    }


    /**
     * Split the keyword into its individial words
     * loop through the words and build a regexpression from them
     * then return the expression
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
            return $regExFull . $keywords[0] . $reg .'/i';
        }

        // If the keyword is a phrase
        for ($i = 0; $i < $len; $i++) {
            trim($keywords[$i]);
        
            // the end of the array
            if ($i  === $len - 1) {
                $regExFull .= $keywords[$i] . $reg . '/i';
                break;
            }

            $regExFull .= $keywords[$i] .$reg;
        }

        return  $regExFull;
    }


    /**
     * populateBlackList
     *
     * @param mixed array
     * @return void
     */
    protected function populateBlackList(array $blacklist = null)
    {
        // parse the blacklist array
        $keywords = preg_split('/[^\w]+/m', $keyword);
        // and store it in the protected variable
    }
}
