<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Activity;
use App\Jobs\DeleteUserAccount;
use Illuminate\Http\File;

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
        $threads = $user->load('profile')
            ->threads()
            ->orderBy('replies_count', 'desc')
            ->limit(5)
            ->get();

        return view('profiles.show', [
            'user' => $user,
            'activities' => Activity::feed($user, $days = 3, $limit = 3),
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
            'hide_activities' => 'boolean|nullable',
            'delete_account' => 'boolean|nullable'
        ]);

        // cannot access the variable if it doesn't exist
        // so do a isset check to avoid errors
        if (isset($validated['delete_account'])) {
            DeleteUserAccount::dispatch($user);
            
            auth()->logout();

            return redirect()
                ->route('threads.index')
                ->with('flash', 'Your account was removed');
        }
        
        // If the hide activities checkbox is unchecked (null), set it's value to 0
        // Normalize the input
        $validated['hide_activities'] = $validated['hide_activities'] ?? 0;

        $updateable = $this->uploadToCloud($validated, $user);
        
        $this->removeTempFiles($validated, $user);

        $user->profile->update($updateable);

        return back()->with('flash', 'Updated your profile');
    }


    /**
     * Loop thorugh the filtered array of non-null inputs,
     * If an input key matches the regex (which looks for '_path' at the end of a string)
     * then upload the file to s3 under id/attribute_name and replace the validated arrays
     * path keys with the path to the corresponding s3 resources.
     *
     * @param array $validated
     * @param App\User $user
     * @return $validated - a mapped over version with local file paths replaced to s3 paths
     */
    protected function uploadToCloud(array $validated, User $user)
    {
        foreach ($validated as $key => $value) {
            $matches = [];
            // if there is any attribute with _path get every word before _path https://regex101.com/r/ZF5VHS/1
            preg_match('/(\w+)_path$/i', $key, $matches);

            // if there is a match upload to s3 bucket ex: user_id/profile_photo.png
            if (isset($matches[0])) {
                \Storage::disk('s3')->putFileAs($user->id, new File(public_path() . '/storage/' . $value), $matches[1] . '.png', 'public');

                $validated[$key] = config('filesystems.disks.s3.url') . '/' . $user->id . '/' . $matches[1] . '.png';
            }
        }

        return $validated;
    }

    /**
     * Loop through the filtered array of non null inputs,
     * If an input ends in _path (meaning it is a path to a file in storage)
     * delete all temp files in it
     *
     * @param array $validated
     * @param User $user
     * @return self
     */
    protected function removeTempFiles(array $validated, User $user)
    {
        foreach ($validated as $key => $value) {
            if (preg_match('/[_]path$/', $key)) {
                // get all files under a users temp directory
                // this returns an array with structure [0 => '4/8930940394903.jpeg]
                $files = \Storage::disk('public')->files($user->id);

                // delete all temp files
                foreach ($files as $file) {
                    \Storage::disk('public')->delete($file);
                }
            }
        }

        return $this;
    }
}
