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
    public function a_user_can_filter_threads_by_recently_active()
    {
        // Given we have a thread that was created one day ago with no replies
        $thread = factory(Thread::class)->create([
            'created_at' => \Carbon\Carbon::now()->subDay()
        ]);


        // however if that thread then gets a reply today
        factory(Reply::class)->create([
            'thread_id' => $thread
        ]);

        // That thread should then be active
        $this->get(route('threads.index', '?active=1'))
            ->assertSee($thread->body);
    }



    /** @test */
    public function a_user_can_filter_threads_by_populartiy()
    {
        // Given we have a thread
        $thread = factory(Thread::class)->create();
 
        // And that thread has 24 replies
        factory(Reply::class, 24)->create([
            'thread_id' => $thread->id
        ]);
    

        // If a user filters by popular querystring
        // then the user wont see the thread
        $this->get('/threads/?popular=1')
            ->assertDontSee($thread->title);

        // However if the thread gets one more reply (25)
        factory(Reply::class)->create([
            'thread_id' => $thread->id
        ]);
        
        // Then it will be listed under popular
        $this->get('/threads/?popular=1')
            ->assertSee($thread->title);
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
