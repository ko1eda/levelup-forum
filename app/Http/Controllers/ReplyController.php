<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Thread;
use App\Reply;
use App\Channel;

class ReplyController extends Controller
{

    public function __construct()
    {
        // user must be authenticated
        $this->middleware('auth');
    }

    /**
     * store
     *
     * @param Channel $channel
     * @param Thread $thread
     * @param Request $req
     * @return void
     */
    public function store(Channel $channel, Thread $thread, Request $req)
    {
        // Remember that $thread
        // already has the correct id
        // bound to it b/c route model binding
        $this->validate($req, [
            'body' => 'required'
        ]);
        
        $thread->addReply([
            'body' => $req->get('body'),
            'user_id' => \Auth::user()->id
        ]);

        return back();
    }

    public function destroy(Request $req, Reply $reply)
    {
        if ($reply->user_id !== \Auth::user()->id) {
            abort(403);
        }

        $reply->delete();

        if ($req->wantsJson()) {
            return response('', 204);
        }
    }
}