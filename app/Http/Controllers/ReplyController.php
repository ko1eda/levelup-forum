<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Thread;
use App\Reply;
use App\Channel;
use App\SpamFilter;

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
    public function store(Thread $thread, Request $req, SpamFilter $spam)
    {
        $this->validate($req, [
            'body' => 'required'
        ]);

        $spam->detect($req->get('body'));

        $thread->addReply([
            'body' => $req->get('body'),
            'user_id' => \Auth::user()->id
        ]);

        $req->session()->flash('flash', 'Posted a Reply!');

        return back();
    }

    /**
     * update
     *
     * @param Reply $req
     * @param Request $req
     * @return void
     */
    public function update(Reply $reply, Request $req)
    {
        // Check if user is authorized to update the given reply
        $this->authorize('update', $reply);

        // Validate the reply
        $this->validate($req, [
            'body' => 'required'
        ]);

        // Update the reply
        $reply->update([
            'body' => $req->get('body')
        ]);

        // hide user info from json response, return code 200
        return response($reply->makeHidden('user')->toJson(), 200);
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