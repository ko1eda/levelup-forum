<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\Registration\ConfirmationSent;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UserRegistered;

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

        // Then an email is sent (note queued here b/c in prod this will be queued)
        Mail::assertQueued(ConfirmationSent::class);
    }


    /** @test */
    public function when_a_user_registers_they_recieve_a_notification_reminding_them_to_confirm_their_email()
    {
        Notification::fake();

        // when the user registers their account
        $this->post(route('register'), [
            'name' => 'user',
            'username' => 'rick',
            'email' => 'user@user.com',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ]);

        $user = User::where('username', 'rick')->first();

        // then the user should recieve a notification
        Notification::assertSentTo($user, UserRegistered::class);
    }


    /** @test */
    public function a_user_can_confirm_their_email_address()
    {
        Mail::fake();
        
        // Given we have an unregistred user
        // and that user registers for an account
        $this->post(route('register'), [
            'name' => 'user',
            'username' => 'rick',
            'email' => 'user@user.com',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ]);

        $user = User::where('username', 'rick')->first();

        // Then the users confirmed property should still be false
        $this->assertFalse($user->confirmed);

        // However their account should now have a non-null confirmation_token set
        $this->assertNotNull($user->confirmation_token);

        // Then when the user vistis the endpoint register/confirmation?tokenID=
        $this->get(route('register.confirm', "tokenID={$user->confirmation_token}"))
            ->assertRedirect(route('threads.index'));

        // Then the users confirmation status should be set to confirmed
        $this->assertTrue($user->fresh()->confirmed);

        // Then the users confirmation token should be set  back to null
        $this->assertNull($user->fresh()->confirmation_token);
    }


    /** @test */
    public function a_user_may_not_use_an_invalid_token_to_confirm_their_email()
    {
        // If a user tries to hit our confirmation endpoint with an invalid tokenID
        // Then that user should hit a 404 not found
        $this->get(route('register.confirm', "tokenID=invalid"))
            ->assertRedirect(route('threads.index'))
            ->assertSessionHas('flash');
    }
}
