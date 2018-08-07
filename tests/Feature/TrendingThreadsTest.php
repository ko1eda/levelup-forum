<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;
use Illuminate\Support\Facades\Redis;

class TrendingThreadsTest extends TestCase
{

    use RefreshDatabase;


    public function setUp()
    {
        parent::setUp();

        Redis::del('trending_threads');
    }


    /** @test */
    public function it_displays_a_list_of_most_viewed_threads()
    {
        // first assert that the redis cache is empty
        $this->assertEmpty(Redis::zrevrange('trending_threads', 0, -1));

        // Given we have two threads
        $threadWith5Visits = factory(Thread::class)->create();

        // one visited 5 times
        for ($i=0; $i < 5; $i++) {
            $this->get(route('threads.show', [$threadWith5Visits->channel, $threadWith5Visits]));
        }
        
        // // and one thread visited 1 times
        $threadWithOneVisit = factory(Thread::class)->create();

        $this->get(route('threads.show', [$threadWithOneVisit->channel, $threadWithOneVisit]));

        // Then we should have a count of two threads in the trending_threads cache
        $this->assertCount(2, Redis::zrevrange('trending_threads', 0, -1));
    
        // note this is just the array of json objects that is returned from redis when you use zrevrange or zrange
        $trending = Redis::zrevrange('trending_threads', 0, -1);

        // then the title of our highest viewed thread should match the title of the first item in the redis cache when it is decoded
        $this->assertEquals($threadWith5Visits->title, json_decode($trending[0])->title);
    }
}
