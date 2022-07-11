<?php

namespace App\Module\Admin;

use App\Models\User;
use App\Module\Admin\Entities\LkAccount;
use App\Module\Admin\Vo\Role;
use App\Module\Common\PhoneNumber;
use App\Module\Customer\Contracts\CourierAccount;
use App\Module\Customer\Contracts\CourierAccountRegistry;
use App\Module\Customer\Models\Customer;
use App\Module\MobileAuth\Contracts\MobileAccountRegistry;
use App\Module\MobileAuth\Contracts\UserId;
use App\Module\PasswordAuth\Models\PasswordAccount;
use App\Nova\Contracts\NovaUserRegistry;

class LkAccountRegistry implements CourierAccountRegistry, MobileAccountRegistry, NovaUserRegistry
{
    private function __construct()
    {
    }

    public static function instance(): self
    {
        return new self();
    }

    public function registerLkAccount(string $name, string $email, string $password, Role $role): LkAccount
    {
        $user = new User();
        $user->saveOrFail();

        $passwordAccount = new PasswordAccount(
            [
                'user_id' => $user->id,
                'email' => $email,
                'name' => $name,
                'password' => bcrypt($password),
            ]
        );

        $passwordAccount->saveOrFail();

        return LkAccount::create($user->id, $role);
    }

    private function registerMobileAccount(Role $role): LkAccount
    {
        $user = new User();
        $user->saveOrFail();

        return LkAccount::create($user->id, $role);
    }

    public function registerCourier(): CourierAccount
    {
        return $this->registerMobileAccount(Role::courier());
    }

    public function registerDemo(): LkAccount
    {
        return $this->registerLkAccount('Demo User', 'demo@mail.com', 'demo', Role::demo());
    }

    public function registerCustomerMobileAccount(PhoneNumber $phoneNumber): UserId
    {
        $lkAccount = $this->registerMobileAccount(Role::customer());

        $customer = new Customer(
            [
                'user_id' => $lkAccount->getUserId(),
                'phone_number' => $phoneNumber->asDbValue(),
            ]
        );

        $customer->saveOrFail();

        return $lkAccount;
    }

    public function isAllowedNovaUser(int $userId): bool
    {
        $lkAccount = LkAccount::find($userId);

        if ($lkAccount === null) {
            return false;
        }

        return $lkAccount->isAllowedLkRole();
    }

    public function removeCourier(CourierAccount $courierAccount): void
    {
        $userId = $courierAccount->getUserId();
        $this->removeUser($userId);
        $this->removeMobileAccount($userId);
    }

    private function removeUser(int $userId): void
    {
        User::destroy([$userId]);
    }

    private function removeMobileAccount(int $userId): void
    {
        $lkAccount = LkAccount::find($userId);

        if ($lkAccount === null) {
            return;
        }

        $lkAccount->remove();
    }
}
