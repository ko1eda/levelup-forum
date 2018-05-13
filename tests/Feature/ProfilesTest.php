<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class ProfilesTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function a_user_has_a_profile()
    {
        // Given a user navigates to
        $user = factory(User::class)->create();

        $route = route('profiles.show', ['name' => $user->name]);
        // /profiles/$user->name
        $this->get($route)
            ->assertSee($user->name);

        // That user should see their name


    }

    /** @test */
    public function an_authenticated_user_can_view_their_profile()
    {
        // Given we have an auth user
        // And that user navigates to /profiles/{username}
        // That user should see their username, email and threads
    }

}
