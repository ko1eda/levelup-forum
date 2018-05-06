<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Thread;

class CreateThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_unauthenticated_user_cannot_create_a_thread()
    {
        $this->checkUnauthFunctionality('post', '/threads');
    }

    /** @test */
    public function an_authenticated_user_can_create_a_thread()
    {
       // Given that we have an authenticated user
        $this->signInUser();

        // And that user makes a POST request to our endpoint
        $thread = factory(Thread::class)->make();
        $this->post('/threads', $thread->toArray());

        // And when the user visits the threads page
        // Then the user should see this new thread
        $this->get($thread->path())
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
