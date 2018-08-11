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


    /** @test */
    public function a_user_can_confirm_their_email_address()
    {
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
}
