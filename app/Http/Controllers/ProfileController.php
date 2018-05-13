<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class ProfileController extends Controller
{
    

    public function show(User $user)
    {
        $threads = $user->threads()->latest()->paginate(1);

        // $user->load(['threads'=> function($query){
        //     $query->latest();
        // }])->get();
        return view('profiles.show', compact('user', 'threads'));
    }
}
