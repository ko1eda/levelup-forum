<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\User;
use App\Channel;

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
    public function create(Request $req)
    {
        if ($data = Redis::get('unconfirmed_channel:' . $req->query('tokenID'))) {
            $data = unserialize($data);

            $user = User::with('profile:profile_photo_path,user_id')->where('id', $data['user_id'])->first();

            $channel = array_only($data, ['slug', 'name', 'description']);

            return view('channels.confirmation.create', [
                'data' => [$user, $channel]
            ]);
        }

        return redirect()
            ->route('threads.index')
            ->with('flash', 'Someone has already handled the request~link');
    }

    /**
     * confirm
     *
     * @return void
     */
    public function store(Request $req, Redis $redis)
    {
        if (!$data = $redis::get('unconfirmed_channel:' . $req->query('tokenID'))) {
            return response('', 404);
        }

        $data = unserialize($data);

        $channel = Channel::create([
            'name' => $data['name'],
            'slug' => $data['slug']
        ]);

        $redis::del('unconfirmed_channel:' . $req->query('tokenID'));
        
        // remove channels list from the cache because it was updated
        $redis::del('channels:list');

        return response($channel->fresh(), 200);
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
