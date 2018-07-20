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

        // And that user hits our images endpoint with
        $filePath = $this->json('post', route('api.uploads.images.store', ['test-avatars', \Auth::user()]), [
            'file' => $file = UploadedFile::fake()->image('image.jpg')
        ])
        ->decodeResponseJson('path');

        // Then that file should be in local storage, under the directory name they specified
        Storage::disk('public')->assertExists($filePath);

        // This will just remove the directory since we cannot use storage fake
        Storage::disk('public')->deleteDirectory('test-avatars');
    }


    /** @test */
    public function an_image_cannot_be_stored_under_a_directory_name_that_we_do_not_allow()
    {
        // Given we have a user
        $this->signInUser();

        // And that user hits our images endpoint with the key lasagna
        // then that user should recieve a 404 response because it is not on our whitelist
        // of appropriate directory names
        $this->json('post', route('api.uploads.images.store', ['lasagna', \Auth::user()]), [
            'file' => UploadedFile::fake()->image('image.jpg')
        ])
        ->assertStatus(404);
    }
}
