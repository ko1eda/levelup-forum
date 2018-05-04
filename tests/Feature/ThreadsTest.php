<?php

namespace Tests\Feature;

use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThreadsTest extends TestCase
{
    // trait used to determine
    // which method your db testing
    // should use.
    use RefreshDatabase;

    // note you need this test docblock
    // for phpunit to work

    /** @test */
    public function a_user_can_view_all_threads()
    {
        $thread = factory(Thread::class)->create();

        // If the uri is
        $response = $this->get('/threads');

        // Then there will be a response of
        $response->assertStatus(200);

        // I will see a thread with
        $response->assertSee($thread->title);
    }
    
    /** @test */
    public function a_user_can_view_a_single_thread()
    {
        $thread = factory(Thread::class)->create();

        // If the uri is matching this threads id
        $response = $this->get('/threads/' .$thread->id);

        // I will see a thread with
        $response->assertSee($thread->title);
    }
}
