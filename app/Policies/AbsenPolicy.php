<?php

namespace App\Policies;

use App\Models\Absen;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AbsenPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        // if($user->hasPermissionTo('View Posts')){
        //     return true;
        // }
        // return false;
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Absen $absen)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        // if($user->hasPermissionTo('View Posts')){
        //     return true;
        // }
        // return false;
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Absen $absen)
    {
        // if($user->hasPermissionTo('View Posts')){
        //     return true;
        // }
        // return false;
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Absen $absen)
    {
        // if($user->hasPermissionTo('View Posts')){
        //     return true;
        // }
        // return false;
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Absen $absen)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Absen $absen)
    {
        //
    }
}
