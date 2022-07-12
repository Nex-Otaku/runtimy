<?php

namespace App\Module\Admin\Access;

use App\Module\Admin\Entities\LkAccount;

class LkAccess
{
    private LkAccount $lkAccount;

    private function __construct(int $userId)
    {
        $lkAccount = LkAccount::find($userId);

        if ($lkAccount === null) {
            throw new \LogicException('Не найден аккаунт ЛК для пользователя с ID ' . $userId);
        }

        $this->lkAccount = $lkAccount;
    }

    public static function of(int $userId): self
    {
        return new self($userId);
    }

    // Couriers

    public function canViewCouriers(): bool
    {
        return $this->lkAccount->isOperator()
            || $this->lkAccount->isOwner()
            || $this->lkAccount->isDeveloper();
    }

    public function canEditCouriers(): bool
    {
        return $this->lkAccount->isOperator()
            || $this->lkAccount->isOwner()
            || $this->lkAccount->isDeveloper();
    }

    public function canAddCouriers(): bool
    {
        return $this->lkAccount->isOperator()
            || $this->lkAccount->isOwner()
            || $this->lkAccount->isDeveloper();
    }

    public function canRemoveCouriers(): bool
    {
        return $this->lkAccount->isOperator()
            || $this->lkAccount->isOwner()
            || $this->lkAccount->isDeveloper();
    }

    // Customers

    public function canViewCustomers(): bool
    {
        return $this->lkAccount->isOperator()
            || $this->lkAccount->isOwner()
            || $this->lkAccount->isDeveloper();
    }

    public function canEditCustomers(): bool
    {
        return false;
    }

    public function canAddCustomers(): bool
    {
        return false;
    }

    public function canRemoveCustomers(): bool
    {
        return false;
    }

    // Orders

    public function canViewOrders(): bool
    {
        return $this->lkAccount->isOperator()
            || $this->lkAccount->isOwner()
            || $this->lkAccount->isDeveloper();
    }

    public function canEditOrders(): bool
    {
        return $this->lkAccount->isOperator()
            || $this->lkAccount->isOwner()
            || $this->lkAccount->isDeveloper();
    }
}
