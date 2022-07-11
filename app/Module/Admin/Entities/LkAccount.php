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

    public static function find(int $userId): ?self
    {
        $lkAccount = LkAccountModel::where(
           [
               'user_id' => $userId,
           ]
        )->first();

        if ($lkAccount === null) {
            return null;
        }

        return new self($lkAccount);
    }

    public function getUserId(): int
    {
        return $this->lkAccount->user_id;
    }

    public function isAllowedLkRole(): bool
    {
        return $this->role()->isAllowedLk();
    }

    public function isOperator(): bool
    {
        return $this->role()->isOperator();
    }

    public function isDeveloper(): bool
    {
        return $this->role()->isDeveloper();
    }

    public function isOwner(): bool
    {
        return $this->role()->isOwner();
    }

    private function role(): Role
    {
        return Role::fromString($this->lkAccount->role);
    }
}
