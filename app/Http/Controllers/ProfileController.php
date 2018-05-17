<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class ProfileController extends Controller
{
    

    /**
     * show
     *
     * @param User $user
     * @return void
     */
    public function show(User $user)
    {
        $activities = $user->activities()
            ->where('created_at', '>=', \Carbon\Carbon::today()->subDays(3))
            ->limit(15)
            ->get()
            ->groupBy(function ($activity) {
                return $activity->created_at->format('l jS F Y');
            });
            
        $threads = $user->threads()
                ->paginate(10);

        return view(
            'profiles.show',
            compact('user', 'activities', 'threads')
        );
    }
}
