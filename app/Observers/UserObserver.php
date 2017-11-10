<?php

namespace App\Observers;

use App\Models\User;
use Auth;

class UserObserver
{
    /**
     * Listen to the User creatig event.
     *
     * @param  User  $user
     * @return void
     */
    public function creating(User $user)
    {
        if ($user->password) {
            $user->password = bcrypt($user->password);
        }
    }

    /**
     * Listen to the User updatig event.
     *
     * @param  User  $user
     * @return void
     */
    public function updating(User $user)
    {
        if ($user->isDirty('password')) {
            $user->password = bcrypt($user->password);
        }
    }

    /**
     * Listen to the User deleting event.
     *
     * @param  User  $user
     * @return void
     */
    public function deleting(User $user)
    {
    }
}