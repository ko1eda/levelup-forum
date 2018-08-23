<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Reply;

class UserTest extends TestCase
{
    use RefreshDatabase;
    
    // /** @test */
    // public function a_user_can_determine_if_their_last_reply_was_within_a_set_range_of_minutes()
    // {
    //     // Given we have a user
    //     $this->signInUser();

    //     // and that user leaves a reply 10 minutes ago
    //     $reply = factory(Reply::class)->create([
    //         'user_id' => \Auth::id(),
    //         'created_at'  =>  \Carbon\Carbon::now()->subMinute(10)
    //     ]);

    //     // if our threshold amount of time between a users replies is 5 minutes
    //     $latestReplyThresholdTimeInMinutes = 5;


    //     // Then the users hasRepliedWithin function will return false
    //     // because the users latest reply is older than the threshold (0-5 minutes in this case)
    //     $this->assertFalse(\Auth::user()->hasRepliedWithin($latestReplyThresholdTimeInMinutes));


    //     // However if they reply again and that time is within the the threshold amount
    //     $reply = factory(Reply::class)->create([
    //         'user_id' => \Auth::id(),
    //         'created_at'  =>  \Carbon\Carbon::now()->subMinute(1)
    //     ]);

    //     // then the function will return true, meaning the user has replied during that time frame
    //     // and therefore should not be able to reply again until the time has passed.
    //     $this->assertTrue(\Auth::user()->hasRepliedWithin($latestReplyThresholdTimeInMinutes));
    // }

    /** @test */
    public function when_a_user_is_created_they_have_a_default_role_of_user()
    {
        // when we create a user
        $user = factory(User::class)->create();

        // refresh the user to get the users_role
        // you have to do this because the model that is returned from create
        // will only have the properties that were assigned when the model was created
        // and a default role of 3 is assgined by mysql everytime a user is inserted
        $user = $user->fresh();

        // then that user is assigned a role of user
        $this->assertEquals(3, $user->role->id);

        // then that user is assigned a role of standard
        $this->assertEquals('user', $user->role->name);
    }
}
