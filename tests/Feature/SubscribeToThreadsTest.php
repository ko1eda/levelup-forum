<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;
use App\Reply;

class SubscribeToThreadsTest extends TestCase
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
        $this->assertCount(1, $thread->subscriptions);

        // and the user should have no notifications currently
        $this->assertCount(0, \Auth::user()->notifications);
    }


    /** @test */
    public function an_authenticated_user_can_subscribe_to_a_thread_only_once()
    {
        // Given we have a signed in user
        $this->signInUser();

        // and a thread
        $thread = factory(Thread::class)->create();

        // If the user subscribes to the thread
        $this->post(route('subscriptions.threads.store', $thread));
    
        // Then the database should contain a subscription for that user
        $this->assertEquals(1, $thread->subscriptions()->count());

        // However if the user tries to subscribe again
        $this->post(route('subscriptions.threads.store', $thread));

        // Then there should still only be one subscription for that user
        $this->assertEquals(1, \App\Subscription::where('user_id', \Auth::user()->id)->count());
    }


    /** @test */
    public function an_authenticated_user_can_unsubscribe_to_a_thread_only_if_they_subscribe_to_it()
    {
        // Given we have a signed in user
        $this->withExceptionHandling()
            ->signInUser();

        // and a thread with a subscription
        $thread = factory(Thread::class)->create();
        $thread->addSubscription();
        $this->assertEquals(1, $thread->subscriptions()->count());

        // And that user hits our subscription endpoint /threads/{thread}/subscriptions
        $this->delete(route('subscriptions.threads.destroy', $thread))
            ->assertStatus(204);

        // Then the database should contain a subscription for that user
        $this->assertEquals(0, $thread->subscriptions->count());

        // And if that user tries to unsubscribe to a thread they aren't subscribed to
        $thread->addSubscription(2);

        // Then the user should recieve a 403 forbidden response
        $this->delete(route('subscriptions.threads.destroy', $thread))
            ->assertStatus(403)
            ->assertSeeText('Access denied');
    }
}
