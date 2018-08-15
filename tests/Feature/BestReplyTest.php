<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Reply;
use App\Thread;
use Illuminate\Support\Facades\Redis;

class BestReplyTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function a_user_who_does_not_own_the_thread_cannot_mark_the_best_reply()
    {
        // Given we have a user
        $this->signInUser();

        // who does not own the thread
        $thread = factory(Thread::class)->create();

        // and two replies for that thread
        $replies = factory(Reply::class, 2)->create(['thread_id' => $thread->id]);

        // if the user tries to mark a reply
        // then that user shoudld recieve a 401 Unauthorized response
        $this->withExceptionHandling()->json('POST', route('replies.best.store', $replies[0]))
            ->assertStatus(403);
    
        // And the reply should not be marked as best
        $this->assertNotEquals($thread->fresh()->best_reply_id, $replies[0]->id);
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

        // mock redis for the test
        Redis::shouldReceive('hset')
            ->once();

        // if the user clicks the button pointing to our api at /api/replies/{reply}/best
        // then status 204 will be returned
        $this->json('POST', route('replies.best.store', $replies[0]))
            ->assertStatus(204);
        
         // then the first reply will be marked as best
        $this->assertEquals($thread->fresh()->best_reply_id, $replies[0]->id);

        // then that reply should be stored in a redis cache
    }


    /** @test */
    public function a_thread_can_fetch_its_best_reply()
    {
        $this->signInUser();

        // given we have a thread
        $thread = factory(Thread::class)->create(['user_id' => \Auth::id()]);

        // with a best reply
        $reply = factory(Reply::class)->create(['thread_id' => $thread->id]);
        
        Redis::shouldReceive('hset');

        // if we mark the reply
        $this->post(route('replies.best.store', $reply));

        Redis::shouldReceive('hget')
            ->withArgs(['thread:' . $thread->id, 'best_reply']);

        $thread->bestReply();

        // then it will return the reply
        // $this->assertInstanceOf('Illuminate\Database\Eloquent\Model', $thread->bestReply());
    }
}
