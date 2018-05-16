<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;
use App\Activity;
use App\Reply;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_records_activity_when_a_thread_is_created()
    {
        // Given we have an authenticated user
        // And that user creates a thread
        $this->signInUser();
        $thread = factory(Thread::class)->create([
            'user_id' => \Auth::user()->id
        ]);

        // Then their should be an activity
        // recorded in the database for that creation
        $this->assertDatabaseHas('activities', [
            'type' => 'created_thread',
            'user_id' => \Auth::user()->id,
            'subject_id' => $thread->id,
            'subject_type' => 'thread'
        ]);

        // and the subject of that activity (aka the morphTo relationship)
        // should be a thread
        $activity = Activity::firstOrFail();

        $this->assertEquals($activity->subject->id, $thread->id);
    }


    // IMPORTANT NOTE THAT RECORDS CREATED
    // FROM TESTING METHODS ARE REMOVED AT THE END OF THE METHOD
    // THE ACTUAL DB IS ROLLEDBACK AT THE END OF ALL TESTS

    // Note: reply creates a thread by default
    // which intern creates another activity
    // for this user
    // This would result in two Activities generated for this test
    // one for the reply, and the created thread from the reply factory
    // that is why assertCount(2)
    /** @test */
    public function it_records_activity_when_a_reply_is_created()
    {
        // Given we have an authenticated user
        $this->signInUser();

        // And that user replies to a thread
        $reply = factory(Reply::class)->create([
            'user_id' => \Auth::user()->id,
        ]);

        
        // Then their should be an activity
        // recorded in the database for that creation
        $this->assertDatabaseHas('activities', [
            'type' => 'created_reply',
            'user_id' => \Auth::user()->id,
            'subject_id' => $reply->id,
            'subject_type' => 'reply'
        ]);

        $this->assertEquals(2, Activity::count());
    }
}
