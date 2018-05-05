<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;
use App\Reply;

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
    public function a_thread_has_an_associated_user()
    {
         // Given we have a thread
         // Then that thread must have an associated User
         $this->assertInstanceOf(\App\User::class, $this->thread->user);
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
}
