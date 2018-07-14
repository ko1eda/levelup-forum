<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadImagesTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function only_members_can_upload_an_avatar()
    {
        $this->checkUnauthFunctionality('post', route('api.profiles.avatar.store', 1), [], '', true, 401);
    }


    /** @test */
    public function a_valid_avatar_must_be_provided()
    {
        // Given we have a user and exception handling is turned on
        $this->signInUser();
        
        // if that user uploads their avatar
        // then that avatar must be in a valid format
        $this->checkUnauthFunctionality('post', route('api.profiles.avatar.store', \Auth::user()), [
            'avatar' => 'not-a-valid-image'
        ], '', true, 422);
    }


    /** @test */
    public function a_user_may_add_their_avatar_to_their_profile()
    {
        // Given we have a user and exception handling is turned on
        $this->signInUser();

        // Set up a fake public disk driver to store our faked avatar
        // this will be cleared out ever time the test is run
        Storage::fake('public');
        
        // if that user hits the avatar endpoint with an avatar
        // Note that you can fake files for tests using the UploadedFile class
        $this->json('post', route('api.profiles.avatar.store', \Auth::user()), [
            'avatar' => UploadedFile::fake()->image('avatar.jpg')
        ]);

        
        // Then that avatar should be persisted to the images table
    }
}
