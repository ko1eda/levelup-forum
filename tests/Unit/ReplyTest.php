<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Reply;

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
}
