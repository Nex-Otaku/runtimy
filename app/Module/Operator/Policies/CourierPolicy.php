<?php

namespace App\Module\Operator\Policies;

use App\Module\Customer\Models\Courier;
use App\Module\PasswordAuth\Models\PasswordAccount;
use Illuminate\Auth\Access\HandlesAuthorization;

class CourierPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Module\PasswordAuth\Models\PasswordAccount  $passwordAccount
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(PasswordAccount $passwordAccount)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Module\PasswordAuth\Models\PasswordAccount  $passwordAccount
     * @param  \App\Models\Courier  $courier
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(PasswordAccount $passwordAccount, Courier $courier)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Module\PasswordAuth\Models\PasswordAccount  $passwordAccount
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(PasswordAccount $passwordAccount)
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Module\PasswordAuth\Models\PasswordAccount  $passwordAccount
     * @param  \App\Models\Courier  $courier
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(PasswordAccount $passwordAccount, Courier $courier)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Module\PasswordAuth\Models\PasswordAccount  $passwordAccount
     * @param  \App\Models\Courier  $courier
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(PasswordAccount $passwordAccount, Courier $courier)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Module\PasswordAuth\Models\PasswordAccount  $passwordAccount
     * @param  \App\Models\Courier  $courier
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(PasswordAccount $passwordAccount, Courier $courier)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Module\PasswordAuth\Models\PasswordAccount  $passwordAccount
     * @param  \App\Models\Courier  $courier
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(PasswordAccount $passwordAccount, Courier $courier)
    {
        return false;
    }
}
