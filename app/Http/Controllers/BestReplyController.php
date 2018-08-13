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
        $key = $reply->thread->markBestReply($reply->id);

        // !app()->environment('testing') ?: $key = 'testing-' . $key;

        Redis::set($key, $reply->makeHidden(['user', 'favorites', 'thread'])->toJson());
        
        return response([], 204);
    }
}
