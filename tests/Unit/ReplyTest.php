<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Reply;
use App\User;

class ReplyTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function a_reply_has_an_associated_user()
    {
        // Given we have a reply
        $reply = factory(Reply::class)->create();

        // Then that reply must have an associated User
        $this->assertInstanceOf(\App\User::class, $reply->user);
    }

    /** @test */
    public function a_reply_is_aware_of_its_mentioned_users()
    {
        // given we have a reply
        factory(User::class)->create([
            'username' => 'coolguy42'
        ]);

        factory(User::class)->create([
            'username' => 'jakesays'
        ]);

        // and 3 users are tagged in the body using the format @username
        $reply = factory(Reply::class)->create([
            'body' => '@coolguy42 hello @jakesays'
        ]);

        // dd($reply->mentionedUsers);
        // if we access that replies mentionedUsers array it should return 1
        $this->assertEquals(2, $reply->mentionedUsers->count());

        // And any given one should be an instance of App\User
        $this->assertInstanceOf(\App\User::class, $reply->mentionedUsers->first());
    }
}
