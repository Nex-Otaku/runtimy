<?php

namespace App\Module\Admin\Entities;

use App\Module\Admin\Models\LkAccount as LkAccountModel;

class LkAccount
{
    private const ROLE_OPERATOR = 'operator';

    private LkAccountModel $lkAccount;

    private function __construct(
        LkAccountModel $lkAccount
    ) {
        $this->lkAccount = $lkAccount;
    }

    public static function createOperator(int $userId): self
    {
        $lkAccount = new LkAccountModel(
            [
                'user_id' => $userId,
                'role' => self::ROLE_OPERATOR,
            ]
        );

        $lkAccount->saveOrFail();

        return new self($lkAccount);
    }
}
