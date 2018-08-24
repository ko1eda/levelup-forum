<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChannelConfirmationController extends Controller
{

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'roles:admin']);
    }



    /**
     * confirm
     *
     * @return void
     */
    public function create()
    {
        // load the channel info from redis based on the query string token
        // pass the information to the view

        echo 'hellooooo';
    }

    /**
     * confirm
     *
     * @return void
     */
    public function store()
    {
      // store the confirmed channel in the database
    }

    /**
     * confirm
     *
     * @return void
     */
    public function delete()
    {
      // delete the nonconfirmed channel from redis
    }
}
