<?php

namespace App\Mail\Registration;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class ConfirmationSent extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * This is shared with the markdown email view
     *
     * @var undefined
     */
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
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
        return $this->subject('Confirm Your email address')
            ->markdown('emails.registration.confirmation');
            
    }
}
