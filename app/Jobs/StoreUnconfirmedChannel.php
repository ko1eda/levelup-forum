<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\POPO\TokenGenerator;
use App\Notifications\ChannelCreated;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redis;
use App\User;

class StoreUnconfirmedChannel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * $this->data
     *
     * @var array
     */
    protected $data;

    /**
     * $this->data
     *
     * @var App\User
     */
    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $data, User $user)
    {
        $this->data = $data;

        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // unset the recaptcha response b/c we don't need it anymore
        unset($this->data['g-recaptcha-response']);
    
        // set the slug equl to a slugified version of the channel name (may change this later)
        $this->data['slug'] = str_slug($this->data['name']);
        
        // strip tags from the description
        $this->data['description'] = strip_tags($this->data['description']);
        
        // store the user to be retrieved later
        $this->data['user_id'] = $this->user->id;

        // set the to use with redis, the token will serve as a confirmation token
        $key = 'unconfirmed_channel:' . $token = TokenGenerator::generate($this->data['slug'], $length = 25);
        
        // serialize the validated data in redis for one week
        Redis::setex($key, (60*60*24*7), serialize($this->data));

        // notify 5 random admins however if the user submitting is an admin
        // do not notify them
        $admins = \App\User::where('role_id', 1)
                    ->where('id', '<>', $this->user->id)
                    ->inRandomOrder()
                    ->limit(5)
                    ->get();
    
        // send notifications to all admins
        Notification::send($admins, new ChannelCreated($this->user, $token));
    }
}
