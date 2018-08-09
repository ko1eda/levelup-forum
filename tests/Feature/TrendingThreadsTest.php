<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;
use Illuminate\Support\Facades\Redis;
use App\Widgets\Trending;

class TrendingThreadsTest extends TestCase
{

    use RefreshDatabase;

    protected $trending;

    public function setUp()
    {
        parent::setUp();

        $this->trending = new Trending(new Redis);

        $this->trending->flush();
    }


    /** @test */
    public function it_displays_a_list_of_most_viewed_threads()
    {
        // first assert that the redis cache is empty
        $this->assertEmpty($this->trending->withScores()->get());

        // Given we have two threads
        $threadWith5Visits = factory(Thread::class)->create();

        // one visited 5 times
        for ($i = 0; $i < 5; $i++) {
            $this->get(route('threads.show', [$threadWith5Visits->channel, $threadWith5Visits]));
        }
        
        // and one thread visited 1 times
        $threadWithOneVisit = factory(Thread::class)->create();

        $this->get(route('threads.show', [$threadWithOneVisit->channel, $threadWithOneVisit]));

        // Then we should have a count of two threads in the trending_threads cache
        $this->assertCount(2, $this->trending->withScores()->get());
    
    
        // then the title of our highest viewed thread should match the title of the first item in the redis cache when it is decoded
        $this->assertEquals($threadWith5Visits->title, ($this->trending->withScores()->get()[0]->title));


        $this->trending->flush();
    }


    /** @test */
    public function if_a_thread_is_deleted_its_entry_is_removed()
    {
        // Given there is a thread
        $thread = factory(Thread::class)->create();

        // And that thread is visited
        $this->get(route('threads.show', [$thread->channel, $thread]));

        // The cache should contain one item
        $this->assertCount(1, $this->trending->get());

        // However if the thread is deleted,
        $thread->delete();

        //the item should also be deleted
        $this->assertCount(0, $this->trending->get());
    }
}
