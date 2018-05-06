<?php

namespace Tests\Feature;

use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Reply;

class ThreadsTest extends TestCase
{
    // trait used to determine
    // which method your db testing
    // should use.
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
    public function a_user_can_view_all_threads()
    {

        // If the uri is /threads
        // Then there will be a response of 200
        // and I will see a thread with a title
        $this->get('/threads')
            ->assertStatus(200)
            ->assertSee($this->thread->title);
    }
    
    /** @test */
    public function a_user_can_view_a_single_thread()
    {
        // If the uri is matching this threads id
        // Then I will see the specific thread with that title
        $this->get($this->thread->path())
            ->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_read_replies_associated_with_a_thread()
    {

        // Given we have a thread
        // Create a reply with its thread_id associated
        // to our test thread
        $reply = factory(Reply::class)
            ->create(['thread_id' => $this->thread->id]);

        // When I visit the uri for the given thread
        // Then I will see the associated reply's body
        $this->get($this->thread->path())
            ->assertSee($reply->body);
    }
}
