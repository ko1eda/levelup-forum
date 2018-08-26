<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Notification;
use App\User;
use App\Notifications\ChannelCreated;
use App\Rules\Recaptcha;
use App\Notifications\ChannelConfirmed;

class ManageChannelsTest extends TestCase
{
    use RefreshDatabase;


    public function setUp()
    {
        parent::setUp();

        // mock recaptcha for any tests
        app()->singleton(Recaptcha::class, function ($app) {
            return \Mockery::mock(Recaptcha::class, function ($mock) {
                $mock->shouldReceive('passes')->andReturn(true);
            });
        });
    }


    /** @test */
    public function an_non_confirmed_user_cannot_create_a_new_channel()
    {
        $this->signInUser(factory(User::class)->states('unconfirmed')->create());

        $this->post(route('channels.store'), [
            'name' => 'sports',
            'description' => 'some text',
            'g-recaptcha-response' => 'some-token'
        ])
        ->assertRedirect(route('threads.index'))
        ->assertSessionHas('flash', 'First confirm your email address~danger');
    }


    /** @test */
    public function an_authenticated_confirmed_user_can_create_a_new_channel()
    {
        Notification::fake();

        // Given we have a user
        $this->signInUser();

        // and 3 administrators
        factory(User::class, 3)->states('admin')->create();


        // and that user navigates to /channels/create
        // and when that user creates a channel
        Redis::shouldReceive('setex');
  
        $this->post(route('channels.store'), [
            'name' => 'sports',
            'description' => 'some text',
            'g-recaptcha-response' => 'some-token'
        ])
        ->assertRedirect(route('threads.index'))
        ->assertSessionHas('flash');

        // then the channel should be stored in redis under the given confirmation key
        
        // (get all users where role is administrator)
        $admins = User::where('role_id', 1)->get();

        // and all administrators should be notified that there is a pending channel request
        Notification::assertSentTo($admins, ChannelCreated::class);
    }


    /** @test */
    public function when_an_administrator_approves_a_channel_it_is_added_to_the_channels_list_and_the_creator_is_notified()
    {
        Notification::fake();

        // given we an admin
        $admin = factory(User::class)->states('admin')->create();

        $this->signInUser($admin);

        // and a pending channel request stored in redis
        Redis::shouldReceive('get', 'unconfirmed_channel:12345')
            ->andReturn(serialize([
                'name' => 'some channel name',
                'slug' => str_slug('some channel name'),
                'user_id' => (factory(User::class)->create())->id
            ]));

        Redis::shouldReceive('del', 'unconfirmed_channel:12345');

        // if an admin sends an ajax request to /channels/confirmation/store?tokenID=
        $res = $this->json('POST', route('channels.confirm.store', 'tokenID=12345'))->json();

        // then the channel should be stored in the database
        $this->assertEquals($res['name'], 'some channel name');

        // and the channel creator should recieve a notification stating that their channel was selected
        Notification::assertTimesSent(1, ChannelConfirmed::class);
    }
}
