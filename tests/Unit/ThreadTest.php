<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;

class ThreadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_thread_has_an_associated_user()
    {
         // Given we have a thread
         $thread = factory(Thread::class)->create();

         // Then that thread must have an associated User
         $this->assertInstanceOf(\App\User::class, $thread->user);
    }

    /** @test */
    public function a_thread_has_replies()
    {
        // Given we have a thread 
        $thread = factory(Thread::class)->create();

        // Then It should have a collection class for potential replies
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $thread->replies);
    }
}
