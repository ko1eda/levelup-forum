<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;
use App\User;

class MentionUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function when_a_user_is_mentioned_in_a_reply_they_recieve_a_notification()
    {
        // Given we have a signed in user
        $johnDoe = factory(User::class)->create();
        
        $this->signInUser($johnDoe);
        
        // and another user
        $coolio = factory(User::class)->create([
            'username' => 'coolio'
        ]);

        // and a thread
        $thread = factory(Thread::class)->create();

        // before coolio is mentioned he should have 0 notifications
        $this->assertEquals(0, $coolio->notifications()->count());

        // however if john doe mentions coolio in a reply using the format @username\s
        $this->post(route('replies.store', $thread), ['body' => 'what @coolio said is correct']);

        // Then coolio should recieve a notification
        $this->assertEquals(1, $coolio->notifications()->count());
    }
}
