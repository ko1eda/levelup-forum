<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;
use App\Channel;

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
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel = null)
    {
        isset($channel)
            ? $threads = $channel
                ->threads()
                ->latest()
                ->limit(25)
                ->get()
            : $threads = Thread::latest()
                ->limit(25)
                ->get();

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
            'body' => 'required',
            'title' => 'required',
            'channel_id' => 'required|exists:channels,id'
        ]);

        $thread = Thread::create([
            'body' => $req->get('body'),
            'title' => $req->get('title'),
            'user_id' => \Auth::user()->id,
            'channel_id' => $req->get('channel_id')
        ]);
        
        return redirect($thread->path());
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

        if ($thread->channel->id === $channel->id) {
            // lazy eager load asscoaited user
            // prevent n+1 problem
            // in threads.show foreach loop
            $replies = $thread
                ->replies()
                ->latest()
                ->get()
                ->load('user');

            return view(
                'threads.show',
                compact('thread', 'replies')
            );
        }
        
        return back()->withErrors([
            'message' => ucfirst(
                "{$thread->title} does not belong to this channel"
            )
        ]);
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
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy(Thread $thread)
    {
        //
    }
}
