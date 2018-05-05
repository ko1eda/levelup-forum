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
        $this->expectException(\Illuminate\Auth\AuthenticationException::class);
        
        $this->post('/threads', []);
    }

    /** @test */
    public function an_authenticated_user_can_create_a_thread()
    {
       // Given that we have an authenticated user
        $user = factory(User::class)->create();
        $this->actingAs($user); // same as be

        // And that user makes a POST request to our endpoint
        $thread = factory(Thread::class)->make([
            'user_id' => $user->id
        ]);
        $this->post('/threads', $thread->toArray());

        // Then the thread should be visible in the database
        $this->assertDatabaseHas('threads', $thread->toArray());

        // And when the user visits the threads page
        // Then the user should see this new thread
        $this->get($thread->path())
            ->assertSee($thread->title);
    }
}
