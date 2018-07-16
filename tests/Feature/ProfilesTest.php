<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Thread;

class ProfilesTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $profileURI;

    public function setUp()
    {
        // Set up test environment
        parent::setUp();

        $this->user = factory(User::class)->create();

        $this->profileURI = route('profiles.show', $this->user);
    }

    /** @test */
    public function a_user_has_a_profile()
    {
        // Given we have a user
        // and that user navigates to their profile        
       // Then That user should see their name
        $this->get($this->profileURI)
            ->assertSee($this->user->name);
    }

    /** @test */
    public function a_profile_displays_all_threads_created_by_a_user()
    {
        // Given we have a user
        // And we have two threads, one created by the user
        $threadByUser = factory(Thread::class)->create([
            'user_id' => $this->user->id
        ]);

        // and one by another user
        $threadNotByUser = factory(Thread::class)->create();

        // If that use views their profile
        // Then they should see only their thread
        $this->get($this->profileURI)
            ->assertSee($threadByUser->title)
            ->assertDontSee($threadNotByUser->title);
    }


    /** @test */
    public function when_a_user_is_created_a_default_profile_is_created()
    {
        // Given we have a potential user
        $registeredUser = factory(User::class)->create();

        // Then their profile relationship should return an instance of App\Profile
        $this->assertInstanceOf(\App\Profile::class, $registeredUser->profile);
    }
}
