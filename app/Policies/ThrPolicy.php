<?php

namespace App\Policies;

use App\Models\Thr;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ThrPolicy
{
    use HandlesAuthorization;
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        if($user->hasRoles('Super Admin')){
            return true;
        }
        return false;

        // return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Thr $thr)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        // if($user->hasRole('Super Admin')){
        //     return true;
        // }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Thr $thr)
    {
        // if($user->hasRole('Super Admin')){
        //     return true;
        // }a
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Thr $thr)
    {
        // if($user->hasRole('Super Admin')){
        //     return true;
        // }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Thr $thr)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Thr $thr)
    {
        //
    }
}
