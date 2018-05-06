<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;
use App\Reply;
use App\User;
use App\Channel;

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
    public function a_thread_can_fetch_its_path()
    {
        // Given we have a thread, 
        // Then that threads path function should return
        $this->assertEquals(
            "/threads/{$this->thread->channel->slug}/{$this->thread->id}",
            $this->thread->path()
        );
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
       
        // When the threads addReply method is called
        $this->thread->addReply([
            'body' => 'jhfkjsld',
            'user_id' => 1
        ]);

        // Then we should see the associated reply in
        // the replies table of the database
        $this->assertDatabaseHas('replies', [
            'body' => 'jhfkjsld',
            'user_id' => 1,
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
}
