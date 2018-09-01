<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Thread;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ProfilesTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $profileURI;

    public function setUp()
    {
        // Set up test environment
        parent::setUp();

        $this->user = factory(User::class)->create();

        $this->profileURI = route('profiles.show', $this->user);

        Storage::fake('s3');
    }

    /** @test */
    public function a_user_has_a_profile()
    {
        // Given we have a user
        // and that user navigates to their profile
       // Then That user should see their name
        $this->get($this->profileURI)
            ->assertSee($this->user->name);
    }

    /** @test */
    public function a_profile_displays_all_threads_created_by_a_user()
    {
        // Given we have a user
        // And we have two threads, one created by the user
        $threadByUser = factory(Thread::class)->create([
            'user_id' => $this->user->id
        ]);

        // and one by another user
        $threadNotByUser = factory(Thread::class)->create();

        // If that use views their profile
        // Then they should see only their thread
        $this->get($this->profileURI)
            ->assertSee($threadByUser->title)
            ->assertDontSee($threadNotByUser->title);
    }


    /** @test */
    public function when_a_user_is_created_a_default_profile_is_created()
    {
        // Given we have a potential user
        $registeredUser = factory(User::class)->create();

        // Then their profile relationship should return an instance of App\Profile
        $this->assertInstanceOf(\App\Profile::class, $registeredUser->profile);
    }


    /** @test */
    public function an_authorized_user_can_only_view_their_own_profile_settings_page()
    {
        // Given we have a registered user
        $this->withExceptionHandling()
            ->signInUser($this->user);

        // And another user who's settings the logged in user should not be able to see
        $userWhosePageShouldNotBeVisible = factory(User::class)->create();

        // if the logged in user navigates to there settings page
        $this->get(route('profiles.settings.edit', \Auth::user()))
            ->assertStatus(200);


        // But if the user tries to access the settings page for another user they
        // will nb
        $this->get(route('profiles.settings.edit', $userWhosePageShouldNotBeVisible))
            ->assertStatus(403);
    }


    /** @test */
    public function a_user_may_add_their_avatar_to_their_profile()
    {
        // Given we have a user
        $this->signInUser();
    
        // if that user hits the avatar endpoint with an avatar
        // Note that you can fake files for tests using the UploadedFile class
        $filePath = $this->json('post', route('api.uploads.images.store', \Auth::user()), [
            'file' => $file = UploadedFile::fake()->image('image.jpg')
        ])
            ->decodeResponseJson('path');
    
        // Then that avatar should be stored under the given file path
        Storage::disk('public')->assertExists($filePath);
        
        
        // And if the user updates the profile settings page (aka in this case submits the image)
        $this->patch(route('profiles.settings.update', \Auth::user()), [
            'avatar_path' => $filePath
        ]);
    
        // Then the stored path name on the users profile should be equal to the
        // avatars path in local storage
        $this->assertEquals('https://s3.amazonaws.com/oneupforum/'. auth()->user()->id . '/avatar.png', \Auth::user()->profile->avatar_path);

    }



    /** @test */
    public function when_a_user_updates_a_file_on_their_profile_all_tempory_files_are_deleted()
    {
        // Given we have a user and exception handling is turned on
        $this->signInUser();

        // if that user hits the avatar endpoint with an avatar
        $this->json('post', route('api.uploads.images.store', \Auth::user()), [
            'file' => UploadedFile::fake()->image('avatar.jpg')
        ]);

        // And then hits that endpoint again
        $filePath2 = $this->json('post', route('api.uploads.images.store', \Auth::user()), [
            'file' => UploadedFile::fake()->image('avatar.jpg')
        ])
            ->decodeResponseJson('path');


        // then that user should have 2 files under thier avatars directory
        $this->assertEquals(2, count(Storage::disk('public')->files(\Auth::id())));

         // however when the user updates their profile (aka submits thier avatar choice)
        $this->patch(route('profiles.settings.update', \Auth::user()), [
            'avatar_path' => $filePath2
        ]);

        // Then their avatars directory should only have one avatar in it
        $this->assertEquals(0, count(Storage::disk('public')->files(\Auth::id())));
    }


    /** @test */
    public function a_users_activity_feed_will_be_hidden_if_they_check_hide_activity_feed_option_on_their_profiles()
    {
        // Given we have a user and that user is logged in
        $this->signInUser();

        // If the user navigates to their profile
        // Then they should see their activity feed
        $this->get(route('profiles.show', \Auth::user()))
            ->assertSee('Recent Activity');

        // However If that user checks Hide Activity Feed in their user profile
        $this->patch(route('profiles.settings.update', \Auth::user()), [
            'hide_activities' => true
        ]);

        // Then that users profile should not display the activity feed
        $this->get(route('profiles.show', \Auth::user()))
            ->assertDontSee('Recent Activity');
    }

    /** @test */
    public function when_a_profile_is_deleted_all_user_related_content_is_deleted_from_storage()
    {
        // given we have a user
        $this->signInUser();

        // if that user hits the avatar endpoint with an avatar
        $filePath = $this->json('post', route('api.uploads.images.store', \Auth::user()), [
            'file' => UploadedFile::fake()->image('avatar.jpg')
        ])->json();


        $this->patch(route('profiles.settings.update', \Auth::user()), [
            'avatar_path' => $filePath['path']
        ]);

        $this->assertTrue(\File::exists(public_path() . '/storage/' . \Auth::id()));

        // when that users profile is deleted
        \Auth::user()->profile->delete();

        // then any directory corresponding to a _path attribute on profile should be deleted so avatar_path, profile_photo_path, banner_path etc
        $this->assertFalse(\File::exists(public_path() . '/storage/' . \Auth::id()));
    }
}
