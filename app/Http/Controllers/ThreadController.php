<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;
use App\Channel;
use App\Filters\ThreadFilter;
use App\Rules\SpamFree;
use App\Rules\Recaptcha;
use App\Widgets\Trending;

class ThreadController extends Controller
{
    /**
     * Trending threads
     *
     * @var App\Widgets\Trending;
     */
    protected $trending;


    public function __construct(Trending $trending)
    {
        $this->middleware('auth')
            ->except(['show', 'index']);


        // make sure the user confirms their email
        // before they post a thread
        $this->middleware('email.confirmation')
            ->only(['store','create']);


        // throttle the show and store route
        // so that users cannot easily manipulate the
        // trending threads count or spam threads
        if (!app()->environment('testing')) {
            $this->middleware('throttle:'. config('spam.throttle.threads.frequency'))
                ->only(config('spam.throttle.threads.routes'));
        }
      
        $this->trending = $trending;
    }


    /**
     * Display a listing of the resource.
     *
     * @param  \App\Channel  $channel
     * @param  \App\Filters\ThreadFilter  $filters
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel = null, ThreadFilter $filters)
    {
        $threads = Thread::latest()->filter($filters);

        // If the channel is set then only load the threads in that channel
        !isset($channel) ?: $threads = $threads->where('channel_id', '=', $channel->id);

        $threads = $threads->paginate(25);

        $trendingThreads = $this->trending->withScores()->get([0, 4]);

        return view('threads.index', compact(['threads', 'trendingThreads']));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }


    /**
     * Store a newly created resource in storage.
     * we are injecting recaptcha here so that we can
     * mock it in our tests see ManageThreadsTest
     *
     * @param  \Illuminate\Http\Request  $req
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req, Recaptcha $recaptcha)
    {
        $validated = $req->validate([
            'body' => ['required', app(SpamFree::class)],
            'title' => ['required', 'max:80', app(SpamFree::class)],
            'channel_id' => 'required|exists:channels,id',
            'g-recaptcha-response' =>  [$recaptcha, 'required']
        ]);

        // push the user_id field into the validated $arrayName = array('' => , );
        $validated["user_id"] = \Auth::id();

        // create thread with validateded array
        $thread = Thread::create($validated);

        return redirect()
            ->route('threads.show', [$thread->channel, $thread, $thread->slug])
            ->with('flash', 'Published A Thread');
    }


    /**
     * Display the specified resource.
     * Prevents the user from accessing a
     * thread unassociated with its given channel
     *
     * @param  \App\Channel  $channel
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show(Channel $channel, Thread $thread, String $slug)
    {
        if ($thread->channel_id === $channel->id) {
            $replies = $thread
                ->replies()
                ->where('id', '<>', $thread->best_reply_id)
                ->with('user.profile')
                ->latest()
                ->paginate(10);

            // increment the threads viewcount
            $thread->views()->increment();

            // Store the visited thread for 24 hours
            $this->trending->store($thread)->withExpireHours($hours = 24);

            return view('threads.show', [
                'thread' => $thread,
                'bestReply' => $thread->bestReply(),
                'replies' => $replies
            ]);
        }

        return back()->with('flash', 'Activity Forbidden~Danger');
    }


    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Channel $channel, Thread $thread, String $slug, Request $req)
    {
        $this->authorize('update', $thread);

        $thread->body = $req->validate(['body' => ['required', app(SpamFree::class)]])['body'];

        $thread->save();

        return response($thread->makeHidden(['user', 'channel']), 200);
    }


    /**
     * Remove the specified resource from storage.
     * Note: that there is a deleting event
     * on the thread model that also
     * removes its replies
     *
     * Return response 204 meaning the
     * request was fufilled but there is
     * no data to include with the response
     * if the request was a json request
     *
     * @param  \App\Channel $channel
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy(Channel $channel, Thread $thread, String $slug)
    {

        // Checks ThreadPolicy to make sure
        // the user has permission aka owns
        // the thread
        // if not it will automatically
        // throw a 403 forbidden response
        $this->authorize('delete', $thread);

        $thread->delete();

        if (request()->wantsJson()) {
            return response([], 204);
        }

        return redirect()
            ->route('threads.index');
    }
}
