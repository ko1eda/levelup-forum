<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Reply;
use App\Thread;

class BestReplyTest extends TestCase
{
    use RefreshDatabase;


    public function a_user_who_does_not_own_the_thread_cannot_mark_the_best_reply()
    {

    }


    /** @test */
    public function the_thread_owner_can_mark_the_best_reply()
    {
        // given a registered user
        $this->signInUser();

        // and a thread that belongs to that user
        $thread = factory(Thread::class)->create(['user_id' => \Auth::id()]);

        // and that thread has a reply
        $replies = factory(Reply::class, 2)->create(['thread_id' => $thread->id]);

        // if the user clicks the button pointing to our api at /api/replies/{reply}/best
        // then status 204 will be returned
        $this->json('POST', route('replies.best.store', $replies[0]))
            ->assertStatus(204);
        
         // then the first reply will be marked as best
        $this->assertTrue($replies[0]->fresh()->isBest());
    }
}
