<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Thread;
use App\Reply;

class ParticipateInForumTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_unauthenticated_user_cannot_reply_to_a_thread()
    {
        $thread = factory(Thread::class)->create();
        
        $this->checkUnauthFunctionality('post', $thread->path('/replies'));
    }

    /** @test */
    public function an_authenticated_user_can_reply_to_a_thread()
    {
        // Given we have a authenticated user
        $this->signInUser();

        // When that user navigates to an existing thread
        $thread = factory(Thread::class)->create();
    
        // And the user posts a reply
        $reply = factory(Reply::class)->make([
            'thread_id' => $thread->id
        ]);
        $this->post($thread->path('/replies'), $reply->toArray());
        
        // Then the reply should be visible on the threads page
        $this->get($thread->path())
            ->assertSee($reply->body);
    }

    /** @test */
    public function a_published_reply_must_have_a_body()
    {
        // Given we have a authenticated user
        $this->signInUser();

        // When that user navigates to an existing thread
        // and makes a reply
        $thread = factory(Thread::class)->create();
        $reply = factory(Reply::class)->make(['body' => null]);
        
        // If that reply does not have a body
        // Then laravel should flash the corresponding
        // error to the session
        $this->withExceptionHandling()
            ->post($thread->path('/replies'), $reply->toArray())
            ->assertSessionHasErrors('body');
    }
}
