<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\User;
use App\Channel;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ChannelConfirmed;

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
     * Show the channels confirmation create form
     *
     * @return void
     */
    public function create(Request $req)
    {
        if (!$data = Redis::get('unconfirmed_channel:' . $req->query('tokenID'))) {
            return redirect()
                ->route('threads.index')
                ->with('flash', 'Someone has already handled the request~link');
        }

        $data = unserialize($data);

        $user = User::with('profile:profile_photo_path,user_id')
            ->where('id', $data['user_id'])->first();

        $channel = array_only($data, ['slug', 'name', 'description']);

        return view('channels.confirmation.create', [
            'data' => [$user, $channel]
        ]);
    }
    

    /**
     * Store the confirmed channel in the db
     * and remove it from redis.
     *
     * Then bust the channel list cache
     *
     * @return void
     */
    public function store(Request $req, Redis $redis)
    {
        if (!$data = $redis::get('unconfirmed_channel:' . $req->query('tokenID'))) {
            return response('Channel not found', 404);
        }

        $data = unserialize($data);

        $channel = Channel::create([
            'name' => $data['name'],
            'slug' => $data['slug']
        ]);

        // notify the channel creator their channel was selected
        Notification::send(
            User::where('id', $data['user_id'])->first(),
            new ChannelConfirmed($channel = $channel->fresh())
        );

        $redis::del('unconfirmed_channel:' . $req->query('tokenID'));
        
        // remove channels list from the cache because it was updated
        $redis::del('channels:list');

        return response($channel, 200);
    }

    
    /**
     * Remove the proposed channel from redis
     *
     * @return void
     */
    public function destroy(Request $req, Redis $redis)
    {
        $response = $redis::del('unconfirmed_channel:' . $req->query('tokenID'));

        // if nothing was deleted
        if ($response === 0) {
            return response('Channel not found', 404);
        }

        return response('', 204);
    }
}
