<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use App\Reply;
use App\Thread;

class ReplyPosted
{
    use Dispatchable;


    /**
     * $thread
     *
     * @var undefined
     */
    public $thread;

    /**
     * $reply
     *
     * @var undefined
     */
    public $reply;


    /**
     * Note that you access it by sql query instead of eager loading
     * the relationship so that each reply will not have the thread relationship attached to it
     *
     * @return void
     */
    public function __construct(Thread $thread, Reply $reply)
    {
        $this->thread = $thread;
        $this->reply = $reply;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
