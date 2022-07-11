<?php

namespace App\Module\Operator\Policies;

use App\Module\Admin\Access\LkAccess;
use App\Module\Customer\Models\Order;
use App\Module\PasswordAuth\Models\PasswordAccount;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function viewAny(PasswordAccount $passwordAccount)
    {
        return LkAccess::of($passwordAccount->user_id)->canViewOrders();
    }

    public function view(PasswordAccount $passwordAccount, Order $order)
    {
        return LkAccess::of($passwordAccount->user_id)->canViewOrders();
    }

    public function create(PasswordAccount $passwordAccount)
    {
        return false;
    }

    public function update(PasswordAccount $passwordAccount, Order $order)
    {
        return LkAccess::of($passwordAccount->user_id)->canEditOrders();
    }

    public function delete(PasswordAccount $passwordAccount, Order $order)
    {
        return false;
    }

    public function restore(PasswordAccount $passwordAccount, Order $order)
    {
        return false;
    }

    public function forceDelete(PasswordAccount $passwordAccount, Order $order)
    {
        return false;
    }
}
