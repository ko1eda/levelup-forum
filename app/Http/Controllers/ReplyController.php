<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Thread;
use App\Reply;
use App\Rules\SpamFree;
use App\Notifications\UserMentioned;

class ReplyController extends Controller
{

    public function __construct()
    {
        // user must be authenticated
        $this->middleware('auth');

        // Only allow only 8 reply requests per 1 minute
        $this->middleware('throttle:8,1')->except('destroy');
    }

    /**
     * Check reply policy to determine if the user has
     * already posted a reply within our alloted amount of time.
     *
     * If they have flash an error to the session,
     *
     * If not validate the reply and return a success message.
     *
     * @param Request $req
     * @param Thread $thread
     * @return void
     */
    public function store(Request $req, Thread $thread)
    {
        try {
            $this->authorize('create', new Reply);
        } catch (\Exception $e) {
            return back()->withErrors('You are posting too frequently, please wait a bit');
        }

        // Validate the body against our spamfree class
        // then pull the value for key 'body' from the returned array
        $reply = $thread->addReply([
            'body' => $req->validate(['body' =>  ['required', app(SpamFree::class)]])['body'],
            'user_id' => \Auth::id()
        ]);

        return back()->with('flash', 'Posted a reply!');
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
        $validated = $req->validate(['body' =>  ['required', app(SpamFree::class)]]);

        // Update the reply
        $reply->update($validated);

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
