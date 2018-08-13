<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;
use App\Reply;
use App\User;
use Illuminate\Validation\Factory;
use Vinkla\Hashids\Facades\Hashids;

class ReadThreadsTest extends TestCase
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
    public function a_user_can_view_all_threads_by_associated_channel()
    {

        // Given we have two threads each belonging to a unique channel
        $seenThread = factory(Thread::class)->create();
        $unseenThread = factory(Thread::class)->create();

        // When we navigate to the route
        // whose channel slug corresponds to
        // ONLY ONE of those channels
        $route = route('threads.index', [
            'channel' => $seenThread->channel->slug
        ]);
        
        // Then we should only see the thread(s)
        // associated with that channel
        $this->get($route)
            ->assertSee($seenThread->title)
            ->assertDontSee($unseenThread->title);
    }
    
    /** @test */
    public function a_threads_id_is_hashid_encoded_when_a_user_views_the_thread()
    {
        // Given we have a thread
        $hashedID = Hashids::connection('threads')->encode($this->thread->id);
        
        // When the user views the thread at the threads.show route
        $expectedRoute = "/threads/{$this->thread->channel->slug}/{$hashedID}/{$this->thread->slug}";

        // the uri should be in the form /threads/channel/encoded_id/slug
        $this->assertEquals(
            $expectedRoute,
            route('threads.show', [$this->thread->channel, $this->thread, $this->thread->slug], false)
        );
    }


    /** @test */
    public function a_user_can_view_a_single_thread()
    {
        // If the uri is matching this threads id
        // Then I will see the specific thread with that title
        $this->get(route('threads.show', [$this->thread->channel, $this->thread, $this->thread->slug]))
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
        $this->get(route('threads.show', [$this->thread->channel, $this->thread, $this->thread->slug]))
            ->assertSee($reply->body);
    }
}
