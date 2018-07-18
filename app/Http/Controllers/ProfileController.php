<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Activity;
use App\Profile;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except('show');
    }


    /**
     * Display the users top threads
     * and activity feed
     *
     * @param User $user
     * @return void
     */
    public function show(User $user)
    {
        $threads = $user->threads()
            ->orderBy('replies_count', 'desc')
            ->limit(5)
            ->get();

        return view('profiles.show', [
            'user' => $user,
            'activities' => Activity::feed($user),
            'threads' => $threads
        ]);
    }


    /**
     * display the profile settings form
     *
     * @param User $user
     * @return void
     */
    public function edit(User $user)
    {
        // Make sure the settings page the user is viewing is theirs
        $this->authorize('edit', $user->profile);

        $user = $user->load('profile');

        return view('profiles.settings.edit', compact('user'));
    }


    /**
     * update the users profile settings
     *
     * @param User $user
     * @return void
     */
    public function update(Request $req, User $user)
    {
        $this->authorize('update', $user->profile);

        $validated = $req->validate([
            'avatar_path' => 'nullable',
            'profile_photo_path' => 'nullable',
            'banner_path' => 'nullable',
            'hide_activities' => 'boolean|nullable'
        ]);

        // Remove any null values from the array of validated key/value pairs
        // this is to ensure that you won't reset any of your settings back to null
        // when you update the form
        $validated = array_filter($validated, function ($val) {
            return !is_null($val);
        });

        // Check if the paths to the files actually exist
        // and remove all temporary files for any updated fields
        $this->checkPathExistence($validated, 'public')
            ->removeTemporaryFiles($validated, 'public', $user);

        // update the users profile
        $user->profile->update($validated);


        return back()->with('flash', 'Updated your profile');
    }


    /**
     * Loop thorugh the filtered array of non-null inputs,
     * If an input key matches the regex (which looks for '_path' at the end of a string)
     * Then check if that path exists in our local storage.
     *
     * If the path does not exist, throw an exception
     * @param array $validated
     * @return self
     */
    protected function checkPathExistence(array $validated, String $disk)
    {
        foreach ($validated as $key => $value) {
            if (preg_match('/[_]path$/', $key)) {

                if (!\Storage::disk($disk)->exists($value)) {
                    throw new \Exception('Invalid Image path Exception');
                }
            }
        }

        return $this;
    }

    /**
     * Loop through the filtered array of non null inputs,
     * If an input ends in _path (meaning it is a path to a file in storage)
     * Then grab the root directory for that file type for the given user, and delete all files
     * whose paths are not the validated path (delete all the tempoary files)
     *
     * @param array $validated
     * @param String $disk
     * @param User $user
     * @return self
     */
    protected function removeTemporaryFiles(array $validated, String $disk, User $user)
    {
        foreach ($validated as $key => $value) {
            if (preg_match('/[_]path$/', $key)) {
                // Get the directory name for a given path
                // ex $value = 'avatars/userid/i0923902492
                // then $temp[0] will be 'avatars'
                $temp = explode('/', $value);

                // get all files under that directory ex: 'avatars/4
                // this returns an array with structure [0 => '/avatars/4/8930940394903.jpeg]
                $files = \Storage::disk($disk)->files($temp[0] . '/' . $user->id);

                // loop through the returned files
                // delete any file that is not equal
                // to the input value for the given path
                foreach ($files as $file) {
                    if ($file !== $value) {
                        \Storage::disk('public')->delete($file);
                    }
                }
            }
        }

        return $this;
    }
}
