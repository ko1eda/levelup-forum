<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Thread;
use App\Channel;

class ManageThreadsTest extends TestCase
{
    use RefreshDatabase;
    

    /** @test */
    public function an_unauthenticated_user_cannot_create_a_thread()
    {
        $this->checkUnauthFunctionality('post', '/threads');
    }

    /** @test */
    public function an_unauthenticated_user_cannot_see_create_thread_page()
    {
        $this->checkUnauthFunctionality('get', '/threads/create');
    }

    /** @test */
    public function an_authenticated_user_can_create_a_thread()
    {
        // Given that we have an authenticated user
        $this->signInUser();

        // And that user makes a POST request to our endpoint
        $thread = factory(Thread::class)->make();
        $response = $this->post('/threads', $thread->toArray());
        
        // And when the user visits the threads page
        // Then the user should see this new thread.
        // Note: that we are testing to make sure the response header
        // redirected to the newly created thread meaning the thread persisted
        $this->get($response->headers->get('Location'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /** @test */
    public function an_authenticated_user_can_delete_their_thread()
    {
        // Given we have an authenticated user
        $user = factory(User::class)->create();
        $this->signInUser($user);

        // And that user has a thread
        $thread = factory(Thread::class)->create([
            'user_id' => $user->id,
        ]);
     
        // When that user sends a DELETE request to threads.show for
        // the given threads id
        $route = route('threads.destroy', [$thread->channel, $thread]);

        // Then the user should be redirected to the homepage
        $this->json('DELETE', $route);
            // ->assertRedirect(route('threads.index'));
        
        // Then the thread should be deleted from the database
        // Note that makeHidden removes the channel information
        // from the array because it is not relevant to the array record
        // the channel information is bound to the array via route model binding
        $this->assertDatabaseMissing(
            'threads',
            $thread->makeHidden('channel')->toArray()
        );

        // Then the associated information should be deleted from the database
    }

    // /** @test */
    // public function an_authenticated_user_cannot_delete_another_users_thread()
    // {
    //     //
    // }


    /**
     *
     * Validation tests start here
     *
     */

    /** @test */
    public function a_published_thread_must_have_a_title()
    {

        $this->publishThread(['title' => null])
            ->assertSessionHasErrors(['title']);
    }

    /** @test */
    public function a_published_thread_must_have_a_body()
    {

        $this->publishThread(['body' => null])
            ->assertSessionHasErrors(['body']);
    }

    /** @test */
    public function a_published_thread_must_have_a_valid_channel()
    {

        // The channel_id must not be null
        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors(['channel_id']);

        // The channel_id must be a valid entry
        $this->publishThread(['channel_id' => 99999999848])
             ->assertSessionHasErrors(['channel_id']);
    }


    // This method is not a test it is being used
    // by the various validation tests 
    // above to publish threads
    public function publishThread($override)
    {

        // Given that we have an authenticated user
        $this->signInUser();

        // And that user creates a thread
        $thread = factory(Thread::class)->make($override);
        
        // If that thread does not have any of the valid data
        // Then laravel will flash a validation error message to the session
        // Note : we turn exception handling off here so we don't just get
        //   a ValidationException thrown
        return $this->withExceptionHandling()
            ->post('/threads', $thread->toArray());
    }
}
