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
        // Cusotom method check for unauthexception
        $this->checkUnauthFunctionality('post', '/threads/1/replies');
    }

    /** @test */
    public function an_authenticated_user_can_reply_to_a_thread()
    {
        // Given we have a authenticated user
        $this->signInUser();

        // When that user navigates to an existing thread
        $thread = factory(Thread::class)->create();
    
        // And the user posts a reply
        // note: we use make here bc
        // were storing it to db with a post request.

        // specifying the thread_id also isn't necessary
        // in this case bc route model binding
        // automatically associates a Thread with the correct id
        // to the request but i left it here anyway
        $reply = factory(Reply::class)->make([
            'thread_id' => $thread->id
        ]);
        // by default laravel will not throw an exception for this
        // if no route exists you must change the settings
        // in your app/exceptions/hanlder render method
        $this->post("/threads/{$thread->id}/replies", $reply->toArray());
        
        // Then the reply should be visible on the page
        $this->get('/threads/'.$thread->id)
            ->assertSee($reply->body);
    }
}
