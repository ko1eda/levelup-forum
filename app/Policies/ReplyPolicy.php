<?php

namespace App\Policies;

use App\User;
use App\Reply;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReplyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the reply.
     *
     * @param  \App\User  $user
     * @param  \App\Reply  $reply
     * @return mixed
     */
    public function view(User $user, Reply $reply)
    {
        //
    }

    /**
     * If the user has not replied within
     * the set number of minutes, then they are authorized to reply
     * again.
     *
     * @param  \App\User  $user
     * @param  \App\Reply  $Reply
     * @return mixed
     */
    public function create(User $user, Reply $reply)
    {
        return !$user->hasRepliedWithin($minutes = 1);
    }

    /**
     * Determine whether the user can update the reply.
     *
     * @param  \App\User  $user
     * @param  \App\Reply  $reply
     * @return mixed
     */
    public function update(User $user, Reply $reply)
    {
        return $reply->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the reply.
     * Make sure to register this policy with your authservice provider
     * otherwise it will not work
     *
     * @param  \App\User  $user
     * @param  \App\Reply  $reply
     * @return mixed
     */
    public function delete(User $user, Reply $reply)
    {
        return $reply->user_id === $user->id;
    }
}
