<?php

namespace App\Policies;

use App\User;
use App\Thread;
use Illuminate\Auth\Access\HandlesAuthorization;

class ThreadPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the thread.
     *
     * @param  \App\User  $user
     * @param  \App\Thread  $thread
     * @return mixed
     */
    public function view(User $user, Thread $thread)
    {
        //
    }

    /**
     * Determine whether the user can create threads.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the thread.
     *
     * @param  \App\User  $user
     * @param  \App\Thread  $thread
     * @return mixed
     */
    public function update(User $user, Thread $thread)
    {
        return $user->id === $thread->user_id;
    }

    /**
     * Determine whether the user can delete the thread.
     *
     * @param  \App\User  $user
     * @param  \App\Thread  $thread
     * @return mixed
     */
    public function delete(User $user, Thread $thread)
    {
        // only the threads owner can edit, delete
        // or update the thread in any way
        return $thread->user_id === $user->id;
    }


    // using these in threads.show view

    /**
     * Return true if the user has any
     * of the specified roles
     *
     * @param User $user
     * @param Thread $thread
     * @return void
     */
    public function lock(User $user, Thread $thread)
    {
        return $user->hasRoles(['admin', 'moderator']);
    }


    /**
     * Return true if the thread was not
     * created by the user
     *
     * @param User $user
     * @param Thread $thread
     * @return void
     */
    public function subscribe(User $user, Thread $thread)
    {
        return $thread->user_id !== $user->id;
    }
}
