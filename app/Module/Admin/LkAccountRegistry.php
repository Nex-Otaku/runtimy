<?php

namespace App\Module\Admin;

use App\Models\User;
use App\Module\Admin\Entities\LkAccount;
use App\Module\Admin\Vo\Role;
use App\Module\Customer\Contracts\CourierAccount;
use App\Module\Customer\Contracts\CourierAccountRegistry;
use App\Module\MobileAuth\Contracts\MobileAccountRegistry;
use App\Module\MobileAuth\Contracts\UserId;
use App\Module\PasswordAuth\Models\PasswordAccount;

class LkAccountRegistry implements CourierAccountRegistry, MobileAccountRegistry
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

    public function registerMobileAccount(Role $role): LkAccount
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

    public function registerCustomerMobileAccount(): UserId
    {
        return $this->registerMobileAccount(Role::customer());
    }
}
