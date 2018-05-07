<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Thread;
use App\Channel;

class CreateThreadsTest extends TestCase
{
    use RefreshDatabase;
    

    /** @test */
    public function an_unauthenticated_user_cannot_create_a_thread()
    {
        $this->checkUnauthFunctionality('post', '/threads');
    }

    /** @test */
    public function an_unauthenticated_user_cannot_see_create_thread_page()
    {
        $this->checkUnauthFunctionality('get', '/threads/create');
    }

    /** @test */
    public function an_authenticated_user_can_create_a_thread()
    {
        // Given that we have an authenticated user
        $this->signInUser();

        // And that user makes a POST request to our endpoint
        $thread = factory(Thread::class)->make();
        $response = $this->post('/threads', $thread->toArray());
        
        // And when the user visits the threads page
        // Then the user should see this new thread.
        // Note: that we are testing to make sure the response header
        // redirected to the newly created thread meaning the thread persisted
        $this->get($response->headers->get('Location'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /** @test */
    public function a_published_thread_must_have_a_title()
    {

        $this->publishThread(['title' => null])
            ->assertSessionHasErrors(['title']);
    }

    /** @test */
    public function a_published_thread_must_have_a_body()
    {

        $this->publishThread(['body' => null])
            ->assertSessionHasErrors(['body']);
    }

    /** @test */
    public function a_published_thread_must_have_a_valid_channel()
    {

        // The channel_id must not be null
        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors(['channel_id']);

        // The channel_id must be a valid entry
        $this->publishThread(['channel_id' => 99999999848])
             ->assertSessionHasErrors(['channel_id']);
    }

    public function publishThread($override)
    {

        // Given that we have an authenticated user
        $this->signInUser();

        // And that user creates a thread
        $thread = factory(Thread::class)->make($override);
        
        // If that thread does not have any of the valid data
        // Then laravel will flash a validation error message to the session
        // Note : we turn exception handling off here so we don't just get
        //   a ValidationException thrown
        return $this->withExceptionHandling()
            ->post('/threads', $thread->toArray());
    }
}
