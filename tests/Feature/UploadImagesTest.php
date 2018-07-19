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
    public function only_members_can_upload_an_image()
    {
        $this->checkUnauthFunctionality('post', route('api.uploads.images.store', ['avatars', 1]), [], '', true, 401);
    }


    /** @test */
    public function a_valid_iamge_must_be_provided()
    {
        // Given we have a user and exception handling is turned on
        $this->signInUser();
        
        // if that user uploads their avatar
        // then that avatar must be in a valid format
        $this->checkUnauthFunctionality('post', route('api.uploads.images.store', ['files', \Auth::user()]), [
            'file' => 'not-a-valid-image'
        ], '', true, 422);
    }



    /** @test */
    public function an_image_will_be_stored_in_a_directory_corresponding_to_the_routes_passed_in_key_and_user_id()
    {
        // Given we have a user
        $this->signInUser();
        // Storage::fake('public');

        // And that user hits our images endpoint with the key lasagna
        $filePath = $this->json('post', route('api.uploads.images.store', ['lasagna', \Auth::user()]), [
            'file' => $file = UploadedFile::fake()->image('image.jpg')
        ])
        ->decodeResponseJson('path');

        // Then that file should be in local storage, under the directory
        Storage::disk('public')->assertExists($filePath);

        // This will just remove the directory since we cannot use storage fake
        Storage::disk('public')->deleteDirectory('lasagna');
    }


    /** @test */
    public function a_user_may_add_their_avatar_to_their_profile()
    {
        // Given we have a user and exception handling is turned on
        $this->signInUser();

        // if that user hits the avatar endpoint with an avatar
        // Note that you can fake files for tests using the UploadedFile class
        $filePath = $this->json('post', route('api.uploads.images.store', ['test-avatars', \Auth::user()]), [
            'file' => $file = UploadedFile::fake()->image('image.jpg')
        ])
        ->decodeResponseJson('path');

        // Then that avatar should be stored under the given file path
        Storage::disk('public')->assertExists($filePath);

        // And if the user updates the profile settings page (aka in this case submits the image)
        $this->post(route('profiles.settings.update', \Auth::user()), [
            'avatar_path' => $filePath
        ]);

        // Then the stored path name on the users profile should be equal to the
        // avatars path in local storage
        $this->assertEquals(asset('storage/'. $filePath), \Auth::user()->profile->avatar_path);

        // clean up after test
        Storage::disk('public')->deleteDirectory('test-avatars/');
    }
}
