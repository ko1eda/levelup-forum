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

    /**
     * Determine if user has permission
     * to delete a given reply.
     * If so redirect them back to
     * the current page or return
     * a status 204.
     * If not return a status 403 forbidden
     *
     * @param Request $req
     * @param Reply $reply
     * @return void
     */
    public function destroy(Request $req, Reply $reply)
    {
        // Checks the delete policy to make sure
        // the user has the proper credentials
        // before they can proceed to delete a post
        // NOTE MAKE SURE YOU REGISTER ANY POLICY
        // IN THE AUTHSERVICEPROVIDER or it wont work
        $this->authorize('delete', $reply);

        $reply->delete();

        if ($req->wantsJson()) {
            return response([], 204);
        }

        return back();
    }
}