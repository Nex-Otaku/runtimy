<?php

namespace App\Module\Operator\Policies;

use App\Module\Admin\Access\LkAccess;
use App\Module\Customer\Models\Courier;
use App\Module\PasswordAuth\Models\PasswordAccount;
use Illuminate\Auth\Access\HandlesAuthorization;

class CourierPolicy
{
    use HandlesAuthorization;

    public function viewAny(PasswordAccount $passwordAccount)
    {
        return LkAccess::of($passwordAccount->user_id)->canViewCouriers();
    }

    public function view(PasswordAccount $passwordAccount, Courier $courier)
    {
        return LkAccess::of($passwordAccount->user_id)->canViewCouriers();
    }

    public function create(PasswordAccount $passwordAccount)
    {
        return LkAccess::of($passwordAccount->user_id)->canAddCouriers();
    }

    public function update(PasswordAccount $passwordAccount, Courier $courier)
    {
        return LkAccess::of($passwordAccount->user_id)->canEditCouriers();
    }

    public function delete(PasswordAccount $passwordAccount, Courier $courier)
    {
        return LkAccess::of($passwordAccount->user_id)->canRemoveCouriers();
    }

    public function restore(PasswordAccount $passwordAccount, Courier $courier)
    {
        return false;
    }

    public function forceDelete(PasswordAccount $passwordAccount, Courier $courier)
    {
        return false;
    }
}
