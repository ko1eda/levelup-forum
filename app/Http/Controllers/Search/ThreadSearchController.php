<?php

namespace App\Http\Controllers\Search;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Thread;
use App\Widgets\Trending;
use Illuminate\Support\Facades\Redis;

class ThreadSearchController extends Controller
{
    /**
     * index
     *
     * @param Request $req
     * @return void
     */
    public function index(Request $req)
    {
        $term = $req->query('q');

        $threads = Thread::search($term)->paginate(25);

        if ($req->wantsJson()) {
            return $threads;
        }

        return view('threads.index', [
            'threads' => $threads,
            'trendingThreads' => (new Trending(new Redis))->withScores()->get([0, 4])
        ]);
    }
}
