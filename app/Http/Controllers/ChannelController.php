<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\POPO\TokenGenerator;
use Illuminate\Support\Facades\Redis;
use App\Notifications\ChannelCreated;
use Illuminate\Support\Facades\Notification;
use App\Rules\Recaptcha;

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
    public function store(Request $req, Redis $redis, Recaptcha $recaptcha)
    {
        $v = $req->validate([
            'name' => 'required|unique:channels,name|max:50',
            'description' => 'required',
            'g-recaptcha-response' =>  [$recaptcha, 'required']
        ]);

        // unset the recaptcha response b/c we don't need it anymore
        unset($v['g-recaptcha-response']);
        
        // set the slug equl to a slugified version of the channel name (may change this later)
        $v['slug'] = str_slug($v['name']);
        
        // strip tags from the description
        $v['description'] = strip_tags($v['description']);
        
        // store the user to be retrieved later
        $v['user_id'] = auth()->id();

        // set the to use with redis, the token will serve as a confirmation token
        $key = 'unconfirmed_channel:' . $token = TokenGenerator::generate($v['slug'], $length = 25);
        
        // serialize the validated data in redis for one week
        $redis::setex($key, (60*60*24*7), serialize($v));

        // notify 5 random admins
        $admins = \App\User::where('role_id', 1)
        ->inRandomOrder()
        ->limit(5)
        ->get();
    
        // send notifications to all admins
        Notification::send($admins, new ChannelCreated(auth()->user(), $token));
        
        return redirect()
            ->route('threads.index')
            ->with('flash', 'Channel awaiting admin approval~link');
    }
}
