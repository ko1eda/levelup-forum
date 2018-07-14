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

        // Store the image to the image table
        // $image = Image::create($validated)

        return response([], 200);
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
