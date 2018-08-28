<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Favorite;
use App\Subscription;
use App\User;

class DeleteUserAccount implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * delete all user information
     *
     * @return void
     */
    public function handle()
    {
        $this->user->profile->delete();

        $this->user->threads->each->delete();

        $this->user->replies->each->delete();

        $this->user->notifications->each->delete();

        Favorite::where('user_id', $this->user->id)->get()->each->delete();

        Subscription::where('user_id', $this->user->id)->get()->each->delete();

        // finally delete the user
        $this->user->delete();
    }
}
