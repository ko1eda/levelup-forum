<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;
use App\Channel;
use App\Filters\ThreadFilter;
use App\Rules\SpamFree;

class ThreadController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')
            ->except(['show', 'index']);
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

        !isset($channel)
            ? : $threads = $threads->where('channel_id', '=', $channel->id);

        $threads = $threads->paginate(25);

        return view('threads.index', compact('threads'));
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
     * show
     *
     * @param  \Illuminate\Http\Request  $req
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        // validate
        $this->validate($req, [
            'body' => ['required', app(SpamFree::class)],
            'title' => ['required', 'max:80', app(SpamFree::class)],
            'channel_id' => 'required|exists:channels,id'
        ]);

        $thread = Thread::create([
            'body' => $req->get('body'),
            'title' => $req->get('title'),
            'user_id' => \Auth::user()->id,
            'channel_id' => $req->get('channel_id')
        ]);

        return redirect($thread->path())
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
    public function show(Channel $channel, Thread $thread)
    {
        if ($thread->channel_id === $channel->id) {
            $replies = $thread
                ->replies()
                ->latest()
                ->paginate(25);

            return view(
                'threads.show',
                compact('thread', 'replies')
            );
        }

        return back()
            ->with('flash', 'Activity Forbidden');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread)
    {
        //
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
    public function destroy(Channel $channel, Thread $thread)
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
