<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Thread;
use App\Channel;
use App\Reply;
use App\Favorite;

class ManageThreadsTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function an_unauthenticated_user_cannot_create_a_thread()
    {
        $this->checkUnauthFunctionality('post', route('threads.index'));
    }

    /** @test */
    public function an_unauthenticated_user_cannot_see_create_thread_page()
    {
        $this->checkUnauthFunctionality('get', route('threads.create'));
    }

    /** @test */
    public function an_authenticated_user_can_create_a_thread()
    {
        // Given that we have an authenticated user
        $this->signInUser();

        // And that user makes a POST request to our endpoint
        $thread = factory(Thread::class)->make();
        $response = $this->post(route('threads.index'), $thread->toArray());
        
        // And when the user visits the threads page
        // Then the user should see this new thread.
        // Note: that we are testing to make sure the response header
        // redirected to the newly created thread meaning the thread persisted
        $this->get($response->headers->get('Location'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /**
     * Deletion tests
     *
     */

    /** @test */
    public function an_unauthorized_user_cannot_delete_a_thread()
    {
        // given we have a thread
        $thread = factory(Thread::class)->create();
        
        // and an unathenticated user tries
        // to delete that thread
        // then they will be redirected to the login page
        $this->checkUnauthFunctionality(
            'delete',
            route('threads.destroy', [$thread->channel, $thread])
        );

        // Given a user is logged in and tries to delete
        // a thread that does not belong to them
        // Assert 403 forbidden, response
        $this->signInUser()
            ->delete(route('threads.destroy', [$thread->channel, $thread]))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authorized_user_can_delete_their_thread()
    {
        // Given we have an authenticated user
        $this->signInUser();

        // And that user has a thread
        $thread = factory(Thread::class)->create([
            'user_id' => \Auth::user()->id
        ]);

        // And that thread has replies
        $reply = factory(Reply::class)->create([
            'thread_id' => $thread->id
        ]);
        
        // When the user deletes their thread
        $route = route('threads.destroy', [$thread->channel, $thread]);

        $this->json('DELETE', $route)
            ->assertStatus(204);
        
        // Then the thread should be deleted from the database
        // Note that makeHidden removes the channel information
        // from the array because it is not relevant to the array record
        // the channel information is bound to the array via route model binding
        $this->assertDatabaseMissing('threads', $thread->makeHidden(['channel', 'is_subscribed'])->toArray());
    }

    /** @test */
    public function when_a_thread_is_deleted_so_is_its_associated_data()
    {

        // Given we have an authorized user
        $this->signInUser();

        // And that user creates a thread
        $thread = factory(Thread::class)->create([
            'user_id' => \Auth::user()->id
        ]);

        // And that thread has replies
        $reply = factory(Reply::class)->create([
            'thread_id' => $thread->id
        ]);

        // And that reply has a favorite
        $favorite = $reply->addFavorite();
        
        // If that user
        $route = route('threads.destroy', [$thread->channel, $thread]);

        // When the user deletes the thread
        $this->delete($route);

        // Then the favorite and reply are also deleted
        $this->assertDatabaseMissing('threads', $thread->makeHidden(['channel', 'is_subscribed'])->toArray());
        $this->assertDatabaseMissing('replies', $reply->makeHidden('is_favorited')->toArray());
        $this->assertDatabaseMissing('favorites', $favorite->toArray());
        
        // Then the associated thread record is deleted
        $this->assertDatabaseMissing('activities', [
            'subject_id' => $thread->id,
            'subject_type' => 'thread'
        ]);

        // Then the associated reply record is also deleted
        $this->assertDatabaseMissing('activities', [
            'subject_id' => $reply->id,
            'subject_type' => 'reply'
        ]);

        // Then the associated favorites for that reply are also deleted
        $this->assertDatabaseMissing('activities', [
            'subject_id' => $favorite->id,
            'subject_type' => 'favorite'
        ]);
    }


    /**
     *
     * Validation tests
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
    protected function publishThread($override)
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
            ->post(route('threads.index'), $thread->toArray());
    }
}
