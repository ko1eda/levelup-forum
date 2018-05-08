<?php

namespace App\Filters;

use Illuminate\Http\Request;

class ThreadFilter extends Filter
{
    
    protected $filters = ['by'];
    
    /**
     * by
     *
     * @param String $userName
     * @return void
     */
    protected function by(String $username)
    {
        return $this->builder->whereHas(
            'user',
            function ($query) use ($username) {
                $query->where('name', '=', $username);
            }
        );
    }

}
