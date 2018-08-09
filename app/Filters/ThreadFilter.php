<?php

namespace App\Filters;

use Illuminate\Http\Request;

class ThreadFilter extends Filter
{

    protected $filters = ['by', 'popular', 'active', 'unresponded'];

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
     * Return threads with 25 replies or higher
     * note that I had to add a fix from
     * https://github.com/laravel/framework/issues/3105
     * to runPaginationCountQuery()
     * @return Builder
     */
    protected function popular()
    {
        // clear out any pre-existing order by clause
        $this->builder->getQuery()->orders = [];

        return $this->builder
            ->havingRaw('replies_count >= 25')
            ->orderBy('replies_count', 'desc');
    }

    /**
     * Return only threads that have no replies
     * ordered by the most recent thread
     *
     * @return Builder
     */
    protected function unresponded()
    {
        // clear out any pre-existing order by clause
        $this->builder->getQuery()->orders = [];

        return $this->builder->has('replies', 0)->latest();
    }

    // /**
    //  * Return only Threads created on the current date
    //  * Where the reply count is 50 or greater, counting only
    //  * replies posted on the current date.
    //  * Then order those threads by reply count DESC
    //  *
    //  * @return Builder
    //  */
    // protected function trending()
    // {
    //     // clear out any pre-existing order by clause
    //     $this->builder->getQuery()->orders = [];

    //     return $this->builder
    //         ->whereRaw('threads.created_at >= CURDATE()')
    //         ->whereHas('replies', function ($query) {
    //             $query->whereRaw('replies.created_at >= CURDATE()');
    //         }, '>=', 50)
    //         ->orderBy('replies_count', 'desc');
    // }

    
    /**
     * Shows threads updated within the last 24 hours
     * with a reply count greater than 0
     *
     * @return void
     */
    protected function active()
    {
        $this->builder->getQuery()->orders = [];

        return $this->builder
            ->where('updated_at', '>', \Carbon\Carbon::now()->subDay())
            ->having('replies_count', '>', 0)
            ->orderBy('updated_at', 'DESC');
    }
}
