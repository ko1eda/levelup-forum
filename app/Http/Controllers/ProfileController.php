<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class ProfileController extends Controller
{
    

    /**
     * show
     *
     * @param User $user
     * @return void
     */
    public function show(User $user)
    {
        $threads = $user
            ->threads()
            ->paginate(10);

        return view('profiles.show', compact('user', 'threads'));
    }
}
