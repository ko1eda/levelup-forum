<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;
use App\User;

class LockThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_standard_user_may_not_lock_a_thread()
    {
        // Given we have an admin user account
        $this->signInUser($standardUser = factory(User::class)->create());

        // if that user visits a thread
        $thread = factory(Thread::class)->create();

        // and hits our lock thread endpoint
        $this->withExceptionHandling()
            ->json('POST', route('threads.lock.store', $thread))
            ->assertStatus(403);

        // then the thread should be locked
        $this->assertFalse($thread->fresh()->locked);
    }


    /** @test */
    public function an_admin_may_lock_a_thread()
    {
        // Given we have an admin user account
        $this->signInUser($admin = factory(User::class)->states('admin')->create());

        // if that user visits a thread
        $thread = factory(Thread::class)->create();

        $this->assertFalse($thread->locked);

        // and hits our lock thread endpoint
        $this->json('POST', route('threads.lock.store', $thread))
            ->assertStatus(204);

        // then the thread should be locked
        $this->assertTrue($thread->fresh()->locked);
    }
    

    /** @test */
    public function a_moderator_may_lock_a_thread()
    {
        // Given we have a moderator user account
        $this->signInUser($mod = factory(User::class)->states('moderator')->create());

        // if that user visits a thread
        $thread = factory(Thread::class)->create();

        $this->assertFalse($thread->locked);

        // and hits our lock thread endpoint
        $this->json('POST', route('threads.lock.store', $thread))
            ->assertStatus(204);

        $this->assertTrue($thread->fresh()->locked);
    }

    /** @test */
    public function a_moderator_or_admin_may_unlock_a_thread()
    {
        // Given we have a moderator user account
        $this->signInUser($admin = factory(User::class)->states('admin')->create());

        // if that user visits a thread
        $thread = factory(Thread::class)->create(['locked' => true]);

        $this->assertTrue($thread->locked);

        // and hits our lock thread endpoint
        $this->json('DELETE', route('threads.lock.destroy', $thread))
            ->assertStatus(204);

        // then the thread shouldn't be locked (should be unlocked)
        $this->assertFalse($thread->fresh()->locked);
    }


    /** @test */
    public function if_a_thread_is_locked_no_replies_can_be_posted()
    {
        // Given we have a registered user
        $this->signInUser();

        // and that user has admin privlages

        // if that user visits a thread
        $thread = factory(Thread::class)->create();

        // and hits our lock thread endpoint
        $thread->lock();


        // then if some user tries to post to the thread
        // we should get an error 423 locked response
        $this->post(route('replies.store', $thread), [
            'body' => 'some text',
            'user_id' => auth()->id()
        ])
            ->assertSessionHas('flash');
    }
}
