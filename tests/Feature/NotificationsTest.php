<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;
use App\User;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    
    /** @test */
    public function a_notification_is_prepared_when_a_subscribed_thread_recieves_a_new_reply_not_by_the_current_user()
    {
        // Given we have a user
        $this->signInUser();

        // And a thread
        $thread = factory(Thread::class)->create();
        
        // Which our user is subscribed to
        $thread->addSubscription();

       // If that user replies to the thread they are subscribed to
        $thread->addReply([
            'user_id' => \Auth::user()->id,
            'body' => 'I am the replies body'
        ]);

        // Then the user should NOT recieve a notification
        $this->assertEquals(0, \Auth::user()->notifications()->count());

        // However if another user replies to a thread the user is subscribed to
        $thread->addReply([
            'user_id' => factory(User::class)->create()->id,
            'body' => 'I am the replies body'
        ]);

        // Then the user SHOULD recieve a notification
        $this->assertEquals(1, \Auth::user()->notifications()->count());
    }


    /** @test */
    public function a_user_can_mark_a_single_notification_as_read()
    { 
        // Given we have a user
        $this->signInUser();
    
        // And a thread
        $thread = factory(Thread::class)->create();
            
        // Which our user is subscribed to
        $thread->addSubscription();
    
        // If another user replies to the thread they are subscribed to
        for ($i = 0; $i < 2; $i++) {
            $thread->addReply([
                'user_id' => factory(User::class)->create()->id,
                'body' => 'I am the replies body'
            ]);
        }

        $firstNotification = \Auth::user()->unreadNotifications()->first();
    
        // And the user recieves 2 notifications
        $this->assertEquals(2, \Auth::user()->unreadNotifications()->count());
    
        // Then if that user hits the endpoint and removes the last notification
        $this->json('PATCH', route('users.notifications.update', [\Auth::user(), $firstNotification->id]));
    
        // Then the notification should be marked as read and there should only be one unread
        $this->assertEquals(1, \Auth::user()->unreadNotifications()->count());
    }


    /** @test */
    public function a_user_can_mark_all_notifications_as_read()
    {
        // Given we have a user
        $this->signInUser();
    
        // And a thread
        $thread = factory(Thread::class)->create();
            
        // Which our user is subscribed to
        $thread->addSubscription();
    
        // If another user replies to the thread they are subscribed to
        for ($i = 0; $i < 2; $i++) {
            $thread->addReply([
                'user_id' => factory(User::class)->create()->id,
                'body' => 'I am the replies body'
            ]);
        }

        // And the user sends a patch request to our endpoint WITHOUT the notificationID
        // wildcard
        $this->json('PATCH', route('users.notifications.update', [\Auth::user()]));

        // Then all the users notifications should be marked as read
        // And the collection will have a count of 0
        $this->assertCount(0, \Auth::user()->fresh()->unreadNotifications);
    }


    /** @test */
    public function a_user_can_fetch_all_notifications()
    {
        // Given we have an authenticated user
        $this->signInUser();
    
        // And a thread
        $thread = factory(Thread::class)->create();
            
        // Which our user is subscribed to
        $thread->addSubscription();
    
        // if that thread gets three new replies
        for ($i = 0; $i < 3; $i++) {
            $thread->addReply([
                'user_id' => factory(User::class)->create()->id,
                'body' => 'I am the replies body'
            ]);
        }
    
        // Then the user should have 3 notifications
        $this->assertCount(3, \Auth::user()->notifications);
    
        // And that user sends an ajax request to our endpoint
        // Then it should return 3 notifications
        $this->json('get', route('users.notifications.index', \Auth::user()))
            ->assertJsonCount(3, '');
    }


    /** @test */
    public function a_user_can_fetch_unread_notifications()
    {
        // Given we have an authenticated user
        $this->signInUser();
    
        // And a thread
        $thread = factory(Thread::class)->create();
     
        // Which our user is subscribed to
        $thread->addSubscription();

        // if that thread gets three new replies
        for ($i = 0; $i < 3; $i++) {
            $thread->addReply([
                'user_id' => factory(User::class)->create()->id,
                'body' => 'I am the replies body'
            ]);
        }

        // Then the user should have 3 notifications
        $this->assertCount(3, \Auth::user()->notifications);

        // However if the user reads one of the notifications
        \Auth::user()->notifications()->first()->markAsRead();
     
        // And that user sends an ajax request WITH unread in the query string
        // Then it should return 2 notifications, those being the unread
        $this->json('get', route('users.notifications.index', ['user' => \Auth::user(), 'unread=1']))
            ->assertJsonCount(2, '');
    }
}
