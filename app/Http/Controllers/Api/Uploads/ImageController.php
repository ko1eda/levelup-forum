<?php

namespace App\Http\Controllers\Api\Uploads;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class ImageController extends Controller
{
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
    public function store(Request $req, String $key, User $user)
    {
        $validated = $req->validate([
            'file' => 'required|image|max:1024'
        ]);

        // process the image

        // Store the avatar in pulbic storage under the users id
        // and store the returned path in a variable
        $filePath = $validated['file']
            ->store("{$key}/{$user->id}", 'public');

        return response(['path' => $filePath], 200);
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
