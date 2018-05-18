<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;
use App\Activity;
use App\Reply;
use App\User;

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

    /** @test */
    public function it_records_activity_when_a_favorite_is_created()
    {
        // Given we have an authenticated user
        $this->signInUser();

        // And that user replies to a thread
        $reply = factory(Reply::class)->create();
        
        // And that reply is then favorited
        // by the user
        $favorite = $reply->addFavorite();
        
        // Then their should be an activity
        // recorded in the database for that creation
        $this->assertDatabaseHas('activities', [
            'type' => 'created_favorite',
            'user_id' => \Auth::user()->id,
            'subject_id' => $favorite->id,
            'subject_type' => 'favorite'
        ]);
    }

    /** @test */
    public function it_fetches_an_activity_feed_for_any_user()
    {
        // Given we have a user
        $this->signInUser();
        
        // And that user has created an older threa
        $olderThread = factory(Thread::class)->create([
            'user_id' => \Auth::user()->id,
            'created_at' => \Carbon\Carbon::now()->subDay()
        ]);

        // Because an activity will be created
        // at the exact time we run the test, regardless
        // of when we set a threads created date
        Activity::first()->update([
            'created_at' => \Carbon\Carbon::now()->subDay()
        ]);

        // And that user has created a newer thread
        $newerThread = factory(Thread::class)->create([
            'user_id' => \Auth::user()->id
        ]);

        // If we fetch the activity feed for that user
        $feed = Activity::feed(\Auth::user(), 15);

        // Then the older thread should be in the returned collection
        // with the given format
        $this->assertTrue($feed->keys()->contains(
            \Carbon\Carbon::now()->subDay()->format('l jS F Y')
        ));

        // And the newer thread should be in the
        // collection with the given format
        $this->assertTrue($feed->keys()->contains(
            \Carbon\Carbon::now()->format('l jS F Y')
        ));
    }
}
