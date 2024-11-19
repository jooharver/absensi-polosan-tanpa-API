<?php

namespace App\Policies;

use App\Models\Office;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OfficePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        if($user->hasPermissionTo('View Posts')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Office $office)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Office $office)
    {
        if($user->hasRole('Super Admin')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Office $office)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Office $office)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Office $office)
    {
        //
    }
}