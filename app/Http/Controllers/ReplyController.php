<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Thread;
use App\Reply;

class ReplyController extends Controller
{

    public function __construct()
    {
        // user must be authenticated
        $this->middleware('auth');
    }

    public function store(Thread $thread, Request $req)
    {

        $this->validate($req, [
            'user_id' => 'required',
            'body' => 'required'
        ]);
        
        $thread->addReply([
            'body' => $req->get('body'),
            'user_id' => \Auth::user()->id
        ]);

        return redirect('/threads/'.$thread->id);
    }
}
