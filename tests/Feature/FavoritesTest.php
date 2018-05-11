<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;
use App\Reply;

class FavoritesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_unauthenticated_user_cannot_favorite_a_reply()
    {
        $reply = factory(Reply::class)->create();

        $route = route('favorites.store', [
            'id' => $reply->id
        ]);

        $this->checkUnauthFunctionality('post', $route, ['favoritable_id' => $reply->id]);
    }

    /** @test */
    public function an_authenticated_user_can_favorite_a_reply()
    {
        // Given we have an authenticated user
        $this->signInUser();
        
        // and a thread with a reply
        // Note: our reply factory generates an
        // associated thread
        $reply = factory(Reply::class)->create();

        $route = route('favorites.store', [
            'id' => $reply->id
        ]);
        
        // If that user click the favorites button aka posts to our endpoint
        $this->post($route);

        // Then the database should contain a favorites object corresponding to the request
        // And the reply should have one favorite
        $this->assertDatabaseHas('favorites', [
            'favoritable_id' => $reply->id,
            'favoritable_type' => 'reply'
        ])
        ->assertCount(1, $reply->favorites);
    }

    /** @test */
    public function an_authenticated_user_cannot_favorite_a_reply_twice()
    {
        // Given we have an authenticated user
        $this->signInUser();
        
        // and a thread with a reply
        // Note: our reply factory generates an
        // associated thread
        $reply = factory(Reply::class)->create();

        $route = route('favorites.store', [
            'id' => $reply->id
        ]);
        
        // When that user tries to
        // favorite the same reply twice
        try {
            $this->post($route);
            $this->post($route);
        } catch (\Exception $e) {
            $this->fail('The same record was persisted twice');
        }

        // Then we should still see only one reply
        $this->assertCount(1, $reply->favorites);
    }
}
