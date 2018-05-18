<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Activity;

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
}
