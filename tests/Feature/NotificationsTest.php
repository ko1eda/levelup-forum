<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;
use App\User;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_notification_is_prepared_when_a_subscribed_thread_recieves_a_new_reply_not_by_the_current_user()
    {
        // Given we have a user
        $this->signInUser();

        // And a thread
        $thread = factory(Thread::class)->create();
        
        // Which our user is subscribed to
        $thread->addSubscription();

       // If that user replies to the thread they are subscribed to
        $thread->addReply([
            'user_id' => \Auth::user()->id,
            'body' => 'I am the replies body'
        ]);

        // Then the user should NOT recieve a notification
        $this->assertEquals(0, \Auth::user()->notifications()->count());

        // However if another user replies to a thread the user is subscribed to
        $thread->addReply([
            'user_id' => factory(User::class)->create()->id,
            'body' => 'I am the replies body'
        ]);

        // Then the user SHOULD recieve a notification
        $this->assertEquals(1, \Auth::user()->notifications()->count());
    }
}
