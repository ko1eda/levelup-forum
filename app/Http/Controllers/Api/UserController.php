<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class UserController extends Controller
{

    /**
     * Get the first 5 users for the like matched
     * query string the user submits from the ReplyForm.vue component.
     * If the string is blank, for example, if the user just hits @, then it will return
     * the first 5 results for the letter a.
     *
     * @param Request $req
     * @return void
     */
    public function index(Request $req)
    {
        $search = $req->query('user') ? $req->query('user') : 'a';

        return User::where('username', 'like', "{$search}%")
            ->limit(5)
            ->get()
            ->pluck('username');
    }
}
