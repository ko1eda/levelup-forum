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
    public function store(Reply $reply, Request $req)
    {
        $reply->addFavorite();

        if ($req->wantsJson()) {
            return response($reply->makeHidden(['user', 'favorites', 'favorites_count']), 200);
        }
        
        return back();
    }

    /**
     * destroy
     *
     * Call the removeFavorite method
     * located on the favoritable trait
     *
     * Thorw a 404 exception if no favorite
     * exists for the given user
     *
     * @param Reply $reply
     * @return void
     */
    public function destroy(Reply $reply)
    {
        $reply->removeFavorite();
        
        return response('', 204);
    }
}
