<?php

namespace App\Module\Operator\Policies;

use App\Module\Admin\Access\LkAccess;
use App\Module\Customer\Models\Customer;
use App\Module\PasswordAuth\Models\PasswordAccount;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;

    public function viewAny(PasswordAccount $passwordAccount)
    {
        return LkAccess::of($passwordAccount->user_id)->canViewCustomers();
    }

    public function view(PasswordAccount $passwordAccount, Customer $customer)
    {
        return LkAccess::of($passwordAccount->user_id)->canViewCustomers();
    }

    public function create(PasswordAccount $passwordAccount)
    {
        return LkAccess::of($passwordAccount->user_id)->canAddCustomers();
    }

    public function update(PasswordAccount $passwordAccount, Customer $customer)
    {
        return LkAccess::of($passwordAccount->user_id)->canEditCustomers();
    }

    public function delete(PasswordAccount $passwordAccount, Customer $customer)
    {
        return LkAccess::of($passwordAccount->user_id)->canRemoveCustomers();
    }

    public function restore(PasswordAccount $passwordAccount, Customer $customer)
    {
        return false;
    }

    public function forceDelete(PasswordAccount $passwordAccount, Customer $customer)
    {
        return false;
    }
}
