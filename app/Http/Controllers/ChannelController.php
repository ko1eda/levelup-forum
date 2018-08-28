<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rules\Recaptcha;
use App\Jobs\StoreUnconfirmedChannel;

class ChannelController extends Controller
{

    /**
     * you must be authorized, and confirmed to create a channel
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'email.confirmation']);
    }


    /**
     * create
     *
     * @return void
     */
    public function create()
    {
        return view('channels.create');
    }

    
    /**
     * store
     *
     * @return void
     */
    public function store(Request $req, Recaptcha $recaptcha)
    {
        $validated = $req->validate([
            'name' => 'required|unique:channels,name|max:50',
            'description' => 'required',
            'g-recaptcha-response' =>  [$recaptcha, 'required']
        ]);

        StoreUnconfirmedChannel::dispatch($validated, auth()->user());
        
        return redirect()
            ->route('threads.index')
            ->with('flash', 'Channel awaiting admin approval~link');
    }
}
