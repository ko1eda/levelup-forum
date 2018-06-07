<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Thread;
use App\Reply;

class ParticipateInForumTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_unauthenticated_user_cannot_reply_to_a_thread()
    {
        $thread = factory(Thread::class)->create();

        $this->checkUnauthFunctionality('post', $thread->path('/replies'));
    }

    /** @test */
    public function an_authenticated_user_can_reply_to_a_thread()
    {
        // Given we have a authenticated user
        $this->signInUser();

        // When that user navigates to an existing thread
        $thread = factory(Thread::class)->create();
    
        // And the user posts a reply
        $reply = factory(Reply::class)->make([
            'thread_id' => $thread->id
        ]);
        $this->post($thread->path('/replies'), $reply->toArray());
        
        // Then the reply should be visible on the threads page
        $this->get($thread->path())
            ->assertSee($reply->body);
    }

    /** @test */
    public function an_unauthenticated_user_cannot_delete_a_reply()
    {
        $reply = factory(Reply::class)->create();

        $this->checkUnauthFunctionality('delete', route('replies.destroy', $reply));
    }


    /** @test */
    public function an_authorized_user_can_only_delete_their_own_replies_and_all_the_associated_data()
    {
        // Given we have a user
        $this->withExceptionHandling()
            ->signInUser();
    
        // And and two replies
        // a reply by the current logged user
        $reply = factory(Reply::class)->create([
            'user_id' => \Auth::user()->id
        ]);
    
        // which was favorited 
        $favorite = $reply->addFavorite();
    
        // And a reply buy another another user
        $replyNotByUser = factory(Reply::class)->create();
    
        // If that user deletes their reply
        // Then they should get 204 sucess response
        $this->json('DELETE', route('replies.destroy', $reply))
            ->assertStatus(204);
    
        // Then the database should no longer have the reply
        $this->assertDatabaseMissing('replies', $reply->makeHidden('is_favorited')->toArray());
            
        // Or its aassociated favorites 
        $this->assertDatabaseMissing('favorites', $favorite->toArray());
            
           
        // However if a the same user tries to delete another users reply
        // Then they should recieve a 403 forbidden response
        $this->json('DELETE', route('replies.destroy', $replyNotByUser))
            ->assertStatus(403);
    }

    /** @test */
    public function an_unauthenticated_user_cannot_update_a_reply()
    {
        $reply = factory(Reply::class)->create();

        $this->checkUnauthFunctionality('patch', route('replies.update', $reply));
    }


    /** @test */
    public function an_authorized_user_can_only_update_their_own_reply()
    {
        // Given we have a user
        $this->withExceptionHandling()
            ->signInUser();

        // and that user has a reply
        $reply = factory(Reply::class)->create([
            'user_id' => \Auth::user()->id
        ]);

        // If that user then updates their reply
        // They will recieve a success status code
        $this->patch(route('replies.update', $reply), [
            'body' => 'updated reply dawg'
        ])
            ->assertStatus(200);

        // Then the database should contain the updated reply
        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => 'updated reply dawg']);

        // However if the user tries to update a reply that they did not create
        $replyNotByUser = factory(Reply::class)->create();

        // Then they will recieve a status code 403, forbidden
        $this->patch(route('replies.update', $replyNotByUser))
            ->assertStatus(403);
    }




    /** @test */
    public function a_published_reply_must_have_a_body()
    {
        // Given we have a authenticated user
        $this->signInUser();

        // When that user navigates to an existing thread
        // and makes a reply
        $thread = factory(Thread::class)->create();
        $reply = factory(Reply::class)->make(['body' => null]);
        
        // If that reply does not have a body
        // Then laravel should flash the corresponding
        // error to the session
        $this->withExceptionHandling()
            ->post($thread->path('/replies'), $reply->toArray())
            ->assertSessionHasErrors('body');
    }
}
