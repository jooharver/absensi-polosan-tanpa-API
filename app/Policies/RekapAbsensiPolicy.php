<?php

namespace App\Policies;

use App\Models\RekapAbsensiView;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RekapAbsensiPolicy
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
    public function view(User $user, RekapAbsensiView $rekapAbsensiView)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RekapAbsensiView $rekapAbsensiView)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RekapAbsensiView $rekapAbsensiView)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, RekapAbsensiView $rekapAbsensiView)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, RekapAbsensiView $rekapAbsensiView)
    {
        //
    }
}
