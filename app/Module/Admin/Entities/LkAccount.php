<?php

namespace App\Module\Admin\Entities;

use App\Module\Admin\Models\LkAccount as LkAccountModel;
use App\Module\Admin\Vo\Role;
use App\Module\Customer\Contracts\CourierAccount;
use App\Module\MobileAuth\Contracts\UserId;

class LkAccount implements CourierAccount, UserId
{
    private LkAccountModel $lkAccount;

    private function __construct(
        LkAccountModel $lkAccount
    ) {
        $this->lkAccount = $lkAccount;
    }

    public static function create(int $userId, Role $role): self
    {
        $lkAccount = new LkAccountModel(
            [
                'user_id' => $userId,
                'role' => $role->toString(),
            ]
        );

        $lkAccount->saveOrFail();

        return new self($lkAccount);
    }

    public function getUserId(): int
    {
        return $this->lkAccount->user_id;
    }
}
