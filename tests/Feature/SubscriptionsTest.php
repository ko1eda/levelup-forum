<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;

class SubscriptionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_unauthenticated_user_cannot_subscribe_to_a_thread()
    {
        $thread = factory(Thread::class)->create();

        $this->checkUnauthFunctionality('post', route('subscriptions.threads.store', $thread));
    }


    /** @test */
    public function an_authenticated_user_can_subscribe_to_a_thread()
    {
        // Given we have a signed in user
        $this->signInUser();

        // and a thread
        $thread = factory(Thread::class)->create();

        // And that user hits our subscription endpoint /threads/{thread}/subscriptions
        $this->post(route('subscriptions.threads.store', $thread))
            ->assertSee('Subscription added');

        // Then the database should contain a subscription for that user
        $this->assertEquals(1, $thread->subscriptions()->count());

        // And when the thread recieves a new post

        // Then the user should recieve a notification relating to that new subscription
    }
}
