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
        $this->get(route('profiles.settings.edit', $this->user))
            ->assertStatus(200);


        // But if the user tries to access the settings page for another user they
        // will nb
        $this->get(route('profiles.settings.edit', $userWhosePageShouldNotBeVisible))
            ->assertStatus(403);
    }


    /** @test */
    public function when_a_user_updates_a_file_on_their_profile_all_related_tempory_files_are_deleted()
    {
        // Given we have a user and exception handling is turned on
        $this->signInUser();

        // Set up a fake public disk driver to store our faked avatar
        // this will be cleared out ever time the test is run
        Storage::fake('public');
        
        // if that user hits the avatar endpoint with an avatar
        $this->json('post', route('api.profiles.avatar.store', \Auth::user()), [
            'avatar' => UploadedFile::fake()->image('avatar.jpg')
        ]);

        // And then hits that endpoint again
        $this->json('post', route('api.profiles.avatar.store', \Auth::user()), [
            'avatar' => $file2 = UploadedFile::fake()->image('avatar.jpg')
        ]);


        $filePath2 = 'avatars/' .\Auth::id() . '/' . $file2->hashName();

        // then that user should have 2 files under thier avatars directory
        $this->assertEquals(2, count(Storage::disk('public')->files('avatars/' . \Auth::id())));

         // however when the user updates their profile (aka submits thier avatar choice)
        $this->post(route('profiles.settings.update', \Auth::user()), [
            'avatar_path' => $filePath2
        ]);

        // Then their avatars directory should only have one avatar in it
        $this->assertEquals(1, count(Storage::disk('public')->files('avatars/' . \Auth::id())));

        // And that avatar should correspond to the avatar that was submitted by the user
        $this->assertEquals($filePath2, Storage::disk('public')->files('avatars/' . \Auth::id())[0]);
    }
}
