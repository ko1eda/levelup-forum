<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reply;
use App\Favorite;

class FavoriteController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Reply $reply)
    {
        Favorite::create([
            'favoritable_id' => $reply->id,
            'favoritable_type' => 'reply',
            'user_id' => \Auth::user()->id
        ]);
    }
}
