<?php

namespace App\Module\MobileAuth\Entities;

use App\Models\MobileAccount as MobileAccountModel;
use App\Models\User;

class MobileAccount
{
    /** @var MobileAccountModel */
    private $mobileAccount;

    private function __construct(
        MobileAccountModel $MobileAccount
    ) {
        $this->mobileAccount = $MobileAccount;
    }

    public static function register(string $phoneNumber): self
    {
        $user = new User();
        $user->saveOrFail();

        $mobileAccount = new MobileAccountModel(
            [
                'user_id' => $user->id,
                'pincode' => null,
                'phone_number' => $phoneNumber,
            ]
        );

        $mobileAccount->saveOrFail();

        return new self($mobileAccount);
    }

    public static function createDemo(): self
    {
        return self::register('+71112223344');
    }
}
