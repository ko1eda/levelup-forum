<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Reply;
use App\User;
use App\Thread;

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


    /** @test */
    public function it_wraps_mentioned_users_usernames_in_anchor_tags()
    {
        // given we have a reply that mentions a user
        $reply = new \App\Reply(['body' => 'hey @fred-savage what is up ?']);

        // then the reply should have the ability to replace any mentioned users with their profile links
        $anchoredBody = "hey <a href=" . route('profiles.show', 'fred-savage') . ">@fred-savage</a> what is up ?";


        $this->assertEquals($anchoredBody, $reply->anchored_body);
    }


    /** @test */
    public function a_replies_body_is_stripped_of_unwanted_tags_and_html()
    {
        // Given we have a reply whose body contains harmful html
        $reply = factory(Reply::class)->create([
            'body' => '<script> () => alert("haha")</script> <p>Whelp, <a href="wwww.yeah.com" @click="alert("aaaaa")"></a></p>'
        ]);
         
 
        // When the replies body is returned, the body should not contain any harmful or unwanted characters
        $this->assertEquals($reply->body, '<p>Whelp, <a href="wwww.yeah.com"></a></p>');
    }
}
