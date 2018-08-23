<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;
use App\Reply;
use App\User;
use App\Channel;
use Vinkla\Hashids\Facades\Hashids;

class ThreadTest extends TestCase
{
    use RefreshDatabase;


    protected $thread;

    public function setUp()
    {
        // Set up test environment
        parent::setUp();

        // Given we have a thread (which is we assign as a property)
        $this->thread = factory(Thread::class)->create();
    }

    /** @test */
    public function a_thread_has_a_decriptive_slug()
    {
        // given we have a thread
        $thread = factory(Thread::class)->create();

        // then that threads slug should be a train-case version of its title
        $this->assertEquals(str_slug($thread->title), $thread->slug);
    }


    /** @test */
    public function a_thread_has_an_associated_user()
    {
         // Given we have a thread
         // Then that thread must have an associated User
        $this->assertInstanceOf(User::class, $this->thread->user);
    }

    /** @test */
    public function a_thread_has_replies()
    {
        // Given we have a thread
        // Then It should have a collection class for potential replies
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $this->thread->replies);
    }

    /** @test */
    public function a_thread_can_add_a_reply()
    {
        // Given we have a thread,
        // and a reply associated with that thread
        $user = factory(User::class)->create();
        // When the threads addReply method is called
        $this->thread->addReply([
            'body' => 'jhfkjsld',
            'user_id' => $user->id
        ]);

        // Then we should see the associated reply in
        // the replies table of the database
        $this->assertDatabaseHas('replies', [
            'body' => 'jhfkjsld',
            'user_id' => $user->id,
            'thread_id' => $this->thread->id
        ]);
    }

    /** @test */
    public function a_thread_belongs_to_a_channel()
    {
        // Given we have a thread
        // Then that thread should have an associated channel (main category)
        $this->assertInstanceOf(Channel::class, $this->thread->channel);
    }


    /** @test */
    public function a_thread_can_be_subscribed_to()
    {
        // Given we have a thread
        // And we call its addSubscription method for a given user
        $user = factory(User::class)->create();
        $this->thread->addSubscription($user->id);

        // Then the databases subscriptions table should contain the corresponding entry
        $this->assertDatabaseHas('subscriptions', [
            'subscribable_id' => $this->thread->id,
            'subscribable_type' => 'thread',
            'user_id' => $user->id
        ])
        ->assertEquals(1, $this->thread->subscriptions()->count());
    }


    /** @test */
    public function a_thread_can_be_unsubscribed_to()
    {
        // Given we have a thread with subscriptions
        $user = factory(User::class)->create();
        $this->thread->subscriptions()->create([
            'user_id' => $user->id
        ]);
        // If we call the removeSubscription method and pass the user
        $this->thread->removeSubscription($user->id);

        // Then the subscriptions count should be zero
        // Then the subscription should be removed from the database
        $this->assertDatabaseMissing('subscriptions', ['subscribable_id' => $this->thread->id, 'user_id' => $user->id])
            ->assertEquals(0, $this->thread->subscriptions()->count());
    }

    
    /** @test */
    public function a_threads_is_subscribed_property_can_determin_if_the_auth_user_has_subscribed()
    {
        // Given we have a logged in user
        $user = factory(User::class)->create();
        $this->signInUser($user);

        // And that user has subscribed to the thread
        $this->thread->addSubscription();

        // Then that threads is_subscribed attribute should return true
        $this->assertTrue($this->thread->is_subscribed);

        // And if that user logs off
        \Auth::logout();

        // Then that threads is_subscribed attribute should return false
        $this->assertFalse($this->thread->is_subscribed);

        // And if the user unsubscribes
        $this->signInUser($user);
        $this->thread->removeSubscription();
        
        // Then that threads is_subscribed attribute should return false
        $this->assertFalse($this->thread->is_subscribed);
    }

    /** @test */
    public function a_threads_body_is_stripped_of_unwanted_tags_and_html()
    {
        // Given we have a thread whose body contains harmful html
        $thread = factory(Thread::class)->create([
            'body' => '<script> () => alert("haha")</script> <p>Whelp, <a href="wwww.yeah.com" @click="alert("aaaaa")"></a></p>'
        ]);
        

        // When the threads body is returned, the body should not contain any harmful or unwanted characters
        $this->assertEquals($thread->body, '<p>Whelp, <a href="wwww.yeah.com"></a></p>');
    }
}
