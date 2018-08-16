<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;
use App\Thread;

class TrackViewsTest extends TestCase
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
    public function a_thread_can_record_its_views()
    {
        $this->thread->views()->clear();
        // Given we have a thread
        // if that thread has no views
        // then its views method will return 0
        $this->assertEquals(0, $this->thread->views()->count());
  
        // However if that thread is then viewd
        $this->thread->views()->increment();
  
        // Then that threads views should be incremented by one
        $this->assertEquals(1, $this->thread->views()->count());

        $this->thread->views()->clear();
    }


    /** @test */
    public function when_a_thread_is_deleted_its_views_are_removed()
    {
        // Given we have a thread
        // And that thread has a view
        $this->thread->views()->increment();
  
        // get the threads info for the key
        $id = $this->thread->id;
  
        // assert the thread exists for its key
        $this->assertEquals(1, Redis::hexists('test-thread' . ':' . $id, 'views'));
  
        // the thread has one view
        $this->assertEquals(1, $this->thread->views()->count());
  
        // If that thread is deleted
        $this->thread->delete();
  
        //then it's view is also delted from our cache
        $this->assertEquals(0, Redis::hexists('test-thread' . ':' . $id, 'views'));
    }
}
