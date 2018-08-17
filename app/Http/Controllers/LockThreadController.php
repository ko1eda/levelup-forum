<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Thread;

class LockThreadController extends Controller
{

    /**
     * set auth and roles middlewares
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth','roles:admin,moderator']);
    }


    /**
     * lock the thread
     *
     * @param Thread $thread
     * @return void
     */
    public function store(Thread $thread)
    {
        $thread->lock();

        return response([], 204);
    }
}
