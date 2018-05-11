<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;
use App\Reply;
use App\User;
use Illuminate\Validation\Factory;

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
    public function a_user_can_filter_threads_by_their_name()
    {
        // Given we have an authenticated user
        $JohnDoe = factory(User::class)->create(['name' => 'John Doe']);
        $this->signInUser($JohnDoe);

        // And that user has a thread
        $threadByJohnDoe = factory(Thread::class)->create([
            'user_id' => $JohnDoe->id
        ]);

        $threadNotByJohnDoe = $this->thread;

        // If that user enters a given username into
        // the query string
        // Then the user should only see threads by the queried user
        $this->get('/threads/?by=John Doe')
            ->assertSee($threadByJohnDoe->title)
            ->assertDontSee($threadNotByJohnDoe->title);
    }


    /** @test */
    public function a_user_can_filter_threads_by_trending()
    {
        // Given we have Three threads created on the same day
        // One with 105 replies
        $trendingThread = factory(Thread::class)->create();

        // One with 100 replies
        $trendingThreadLessReplies = factory(Thread::class)->create();
        
        // And one with sub100 replies
        $notTrendingThread = $this->thread;

        for ($i=0; $i < 105; $i++) {
            factory(Reply::class)->create([
                'thread_id' => $trendingThread->id
            ]);
        }

        for ($i=0; $i < 100; $i++) {
            factory(Reply::class)->create([
                'thread_id' => $trendingThreadLessReplies->id
            ]);
        }
        
        // If a user filters by the trending=1 querystring
        // Then the user will see the threads with 105 and 100 replies respectively
        // Then the user will not see the thread with sub100 replies
        $this->get('/threads/?trending=1')
            ->assertSeeInOrder([
                $trendingThread->title,
                $trendingThreadLessReplies->title
            ])
            ->assertDontSee($notTrendingThread->title);
    }

    /** @test */
    public function a_user_can_filter_threads_by_populartiy()
    {
        // Given we have three threads
        $threads = [
            $mostPopularThread = factory(Thread::class)->create(),
            $semiPopularThread = factory(Thread::class)->create(),
            $leastPopularThread = factory(Thread::class)->create()
        ];

        // And those threads have 3,2, and 1 replies respectively
        $numReplies = 3;
        foreach ($threads as $thread) {
            factory(Reply::class, $numReplies--)->create([
                'thread_id' => $thread
            ]);
        }
        
        // If a user filters by popular querystring
        // then the user should see those threads,
        // sorted by reply count in descending order
        $this->get('/threads/?popular=1')
            ->assertSeeinOrder([
                $mostPopularThread->title,
                $semiPopularThread->title,
                $leastPopularThread->title,
            ]);
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
