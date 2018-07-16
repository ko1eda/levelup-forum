<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Activity;
use App\Profile;

class ProfileController extends Controller
{
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
    public function create(User $user)
    {
        // Make sure the profile the user is viewing is theirs
        $this->authorize('create', $user->profile);


        return view('profiles.settings.create', compact('user'));
    }
}
