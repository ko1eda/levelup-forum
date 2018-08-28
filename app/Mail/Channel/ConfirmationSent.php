<?php

namespace App\Mail\Channel;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class ConfirmationSent extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     *
     * @var undefined
     */
    public $user;


    /**
     * the uri to the conmfirmation page
     *
     * @var undefined
     */
    public $uri;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, String $uri)
    {
        $this->user = $user;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('A user has proposed a new channel')
            ->markdown('emails.channel.created');
    }
}
