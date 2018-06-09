<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Thread;
use App\Reply;

class FilterThreadsTest extends TestCase
{

    use RefreshDatabase;

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

        $threadNotByJohnDoe = factory(Thread::class)->create();

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
        $notTrendingThread = factory(Thread::class)->create();

        for ($i = 0; $i < 55; $i++) {
            factory(Reply::class)->create([
                'thread_id' => $trendingThread->id
            ]);
        }

        for ($i = 0; $i < 50; $i++) {
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
    public function a_user_can_filter_threads_by_unresponded()
    {
        // Given we have three threads
        // One with no replies that is older
        $threadNoReplies = factory(Thread::class)->create([
            'created_at' => \Carbon\Carbon::now()->subDay()
        ]);

        // One with no replies that is more recent
        $threadNoRepliesLatest = factory(Thread::class)->create();

        // and one with replies
        $threadWithReply = factory(Thread::class)->create();

        factory(Reply::class)->create([
            'thread_id' => $threadWithReply
        ]);


        // When the user selects the unresponded threads filter
        // They should the the latest unresponded first
        // The older unresponded second
        // And they should not see the thread with replies
        $this->get(route('threads.index', '?unresponded=1'))
            ->assertSeeInOrder([
                $threadNoRepliesLatest->title,
                $threadNoReplies->title
            ])
            ->assertDontSee($threadWithReply->title);
    }
}
