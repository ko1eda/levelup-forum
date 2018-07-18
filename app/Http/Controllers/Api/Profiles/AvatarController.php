<?php

namespace App\Http\Controllers\Api\Profiles;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class AvatarController extends Controller
{

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req, User $user)
    {
        // Validate the request
        $validated = $req->validate([
            'avatar' => 'required|image'
        ]);

        // process the image

        // Store the avatar in pulbic storage under the users id
        // and store the returned path in a variable
        $avatarPath = $validated['avatar']->store("avatars/{$user->id}", 'public');

    
        return response(['path' => $avatarPath], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
