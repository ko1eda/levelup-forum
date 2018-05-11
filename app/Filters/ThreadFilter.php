<?php

namespace App\Filters;

use Illuminate\Http\Request;

class ThreadFilter extends Filter
{
    
    protected $filters = ['by', 'popular', 'trending'];
    
    /**
     * Return all threads for a given
     * user
     *
     * @param String $userName
     * @return Builder
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

    /**
     * Return all threads in desc order
     * by replies
     *
     * @return Builder
     */
    protected function popular()
    {
        // clear out any pre-existing order by clause
        $this->builder->getQuery()->orders = [];

        return $this->builder->orderBy('replies_count', 'desc');
    }

    /**
     * Return only Threads created on the current date
     * having 100 or more replies ordered by reply count
     *
     * Note that the commented out portion
     * returns the same results
     * it's just split into two queries
     *
     * @return Builder
     */
    protected function trending()
    {
        // clear out any pre-existing order by clause
        $this->builder->getQuery()->orders = [];

        return $this->builder->whereHas(
            'replies',
            function ($query) {
                // $query->havingRaw('count(*) >= 25');
                $query->whereDate('created_at', '=', \Carbon\Carbon::today())
                    ->orderBy('count(*)', 'desc');
            },'>=', 100
        );

        // ->whereHas(
        //     'replies',
        //     function ($query) {
        //         $query->whereDate('created_at', '=', \Carbon\Carbon::today());
        //     }
        // );
    }
}
