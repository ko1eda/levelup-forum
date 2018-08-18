<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Thread;

class LockThreadController extends Controller
{

    /**
     * set auth throttle and roles middlewares
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth','roles:admin,moderator', 'throttle:10,1']);
    }


    /**
     * toggle thread lock and unlock
     *
     * @param Thread $thread
     * @return void
     */
    public function store(Thread $thread)
    {
        $thread->locked = true;

        $thread->save();

        return response([], 204);
    }


    /**
     * unlock the thread
     *
     * @param Thread $thread
     * @return void
     */
    public function destroy(Thread $thread)
    {
        $thread->locked = false;

        $thread->save();
  
        return response([], 204);
    }
}
