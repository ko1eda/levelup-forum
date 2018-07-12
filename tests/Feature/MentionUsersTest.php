<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;
use App\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UserMentioned;
use App\Notifications\ThreadUpdated;

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


    /** @test */
    public function the_subscribers_to_a_thread_will_not_recieve_a_thread_updated_notification_if_they_are_mentioned()
    {
        Notification::fake();

        $johnDoe = factory(User::class)->create();
        $this->signInUser($johnDoe);
        
        // and another user
        $coolio = factory(User::class)->create([
            'username' => 'coolio'
        ]);

        // and a thread
        $thread = factory(Thread::class)->create();

        // and a thread
        $thread->addSubscription($coolio->id);

        // before coolio is mentioned he should have 0 notifications
        $this->assertEquals(0, $coolio->notifications()->count());

        // however if john doe mentions coolio in a reply using the format @username\s
        $this->post(route('replies.store', $thread), ['body' => 'what @coolio said is correct']);

        // Then coolio should recieve a notification
        Notification::assertSentTo($coolio, UserMentioned::Class);

        Notification::assertSentToTimes($coolio, ThreadUpdated::class, 0);
    }


    /** @test */
    public function when_the_api_users_index_endpoint_is_pinged_it_will_return_5_relevant_users()
    {
        // given we have a query string with the letters name=joe
        $endpoint = route('api.users.index') .'?user=joe';

        // and 4 users joe, joel, joanne and dan
        $joe = factory(User::class)->create([
            'username' => 'joeeeeeeee'
        ]);

        $joel = factory(User::class)->create([
            'username' => 'joeldude'
        ]);

        $joanne = factory(User::class)->create([
            'username' => 'joanne-princess'
        ]);

        $dan = factory(User::class)->create([
            'username' => 'danTHEman'
        ]);

        // When we hit that endpoint  but joanne will not
        $returnedUserNames = $this->json('GET', $endpoint)->decodeResponseJson();

        // then joe and joel will be returned
        $this->assertEquals([ $joe->username, $joel->username], $returnedUserNames);

        // then joanne and dan will not
        $this->assertNotContains([$joanne->username, $dan->username], $returnedUserNames);
    }
}
