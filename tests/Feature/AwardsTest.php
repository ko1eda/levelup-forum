<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;
use App\User;

class AwardsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function when_a_thread_is_created_that_user_recieves_a_thread_created_award()
    {
        // Given we have a user
        $this->signInUser($user = factory(User::class)->create());
        
        // and that user creates a thread
        factory(Thread::class)->create(['user_id' => auth()->id()]);

        // then that user should recieve a thread_created award
        $this->assertEquals('thread_created', $user->awards()->firstOrFail()->type);
    }

    /** @test */
    public function when_a_reply_is_created_that_user_recieves_a_reply_created_award()
    {
        // Given we have a user
        $this->signInUser($user = factory(User::class)->create());
        
        // and that user creates a thread
        $thread = factory(Thread::class)->create();

        $thread->addReply(['body' => 'hello', 'user_id' => $user->id]);

        // then that user should recieve a reply_created award
        $this->assertEquals('reply_created', $user->awards()->firstOrFail()->type);
    }

    /** @test */
    public function when_a_reply_is_marked_best_the_user_recieves_a_best_reply_marked_or_best_reply_removed_award()
    {
        // Given we have a user
        $user = factory(User::class)->create();

        // and a logged in user
        $this->signInUser();
        
        // who creates a thread
        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);

        // if our non-logged in user replies to that thread
        $reply = $thread->addReply(['body' => 'hello', 'user_id' => $user->id]);

        // and that thread is then marked as the best reply
        $this->json('POST', route('replies.best.store', $reply));

        // then the replies owner should recieve a best_reply_marked award
        $this->assertCount(1, $user->awards()->where('type', 'best_reply_marked')->get());

        // however if a new users reply is marked best
        $newBestReply = $thread->addReply(['body' => 'hello', 'user_id' => factory(User::class)->create()->id]);

        // then the previous user should have a best_reply_removed award
        $this->json('POST', route('replies.best.store', $newBestReply));

        // then the previous user should have a best_reply_removed award
        $this->assertCount(1, $user->awards()->where('type', 'best_reply_removed')->get());
    }
}
