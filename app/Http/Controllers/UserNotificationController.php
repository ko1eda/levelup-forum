<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserNotificationController extends Controller
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
     * Return a jsonified collection
     * of notifications for the logged in user.
     *
     * If the query string contains unread=1
     * Return only the unread notifications
     *
     * @param User $user
     * @return void
     */
    public function index(User $user, Request $req)
    {
        $query = $req->query('unread');

        return (
            isset($query)
                ? \Auth::user()->unreadNotifications
                : \Auth::user()->notifications
        );
    }

    /**
     * Return the first model instance of the specified notification
     * for the authenticated user and then mark it as read.
     *
     * However if no specific notificationID is specified
     * all unread notifications will be marked as read.
     */
    public function update(User $user, $notificationID = null)
    {

        isset($notificationID)
            ? \Auth::user()->notifications()
                ->findOrFail($notificationID)
                ->markAsRead()

            : \Auth::user()
                ->unreadNotifications
                ->markAsRead();
        
        return response([], 200);
    }


    /**
     * Return the first model instance of the specified notification
     * for the authenticated user and then mark it as read.
     *
     * However if no specific notificationID is specified
     * all unread notifications will be marked as read.
     */
    // public function destroy(User $user, $notificationID = null)
    // {
    //     isset($notificationID)
    //         ? \Auth::user()->notifications()
    //             ->findOrFail($notificationID)
    //             ->markAsRead()

    //         : \Auth::user()
    //             ->unreadNotifications
    //             ->markAsRead();
    // }
}
