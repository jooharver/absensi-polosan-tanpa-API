<?php

namespace App\Policies;

use App\Models\SetThr;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class SetThrPolicy
{
    use HandlesAuthorization;
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        if($user->hasRole('Super Admin')){
            return true;
        }
        return false;
        // return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SetThr $setThr)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        if($user->hasRole('Super Admin')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SetThr $setThr)
    {
        if($user->hasRole('Super Admin')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SetThr $setThr)
    {
        if($user->hasRole('Super Admin')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SetThr $setThr)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SetThr $setThr)
    {
        //
    }
}
