<?php

namespace App\POPO;

/**
 * undocumented class
 */
class TokenGenerator 
{
    
    /**
     * Generate a unique confirmation token 
     *
     * @param mixed $value the unique value to base the token on 
     * @param int int
     * @return void
     */
    public static function generate($value, int $size = 25) : string
    {
        return str_limit(md5($value . str_random()), $size, '');
    }

}
