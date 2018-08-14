<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Reply;
use Illuminate\Support\Facades\Redis;

class BestReplyController extends Controller
{

    /**
     * Mark the threads best reply
     * And store it in redis
     *
     * @param Reply $reply
     * @return void
     */
    public function store(Reply $reply)
    {
        // check the threads policy for update permission
        $this->authorize('update', $reply->thread);

        $key = $reply->thread->markBestReply($reply->id);

        Redis::hset($key, 'best_reply', $reply->makeHidden(['user', 'favorites', 'thread'])->toJson());
        
        return response([], 204);
    }
}
