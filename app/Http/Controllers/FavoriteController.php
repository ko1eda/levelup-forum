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

    /**
     * store
     *
     * @param Reply $reply
     * @return void
     */
    public function store(Reply $reply)
    {
        $reply->addFavorite();
    }   
}
