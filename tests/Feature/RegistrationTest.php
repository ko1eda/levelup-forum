<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\Registration\ConfirmationSent;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_confirmation_email_is_sent_to_a_newly_registered_user()
    {
        // fake the sent email
        // how this works is it replaces
        // the instance of the class in the laravel container
        // with a Fake, that fake collects the 'sent emails' in
        // an array for testing.
        Mail::fake();

        // when that user navigates to /register and registers an account
        event(new \Illuminate\Auth\Events\Registered($user = factory(User::class)->create()));

        // Then an email is sent
        Mail::assertSent(ConfirmationSent::class);
    }
}
