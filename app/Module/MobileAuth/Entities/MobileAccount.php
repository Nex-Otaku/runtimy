<?php

namespace App\Module\MobileAuth\Entities;

use App\Models\MobileAccount as MobileAccountModel;
use App\Models\User;
use App\Module\MobileAuth\PincodeSender;

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

    public static function existsByPhone(string $phoneNumber): bool
    {
        return MobileAccountModel::where(['phone_number' => $phoneNumber])->exists();
    }

    public function sendPincode(): void
    {
        $pincode = (new PincodeSender())->sendPincode($this->mobileAccount->phone_number);
        $this->mobileAccount->pincode = $pincode;
        $this->mobileAccount->saveOrFail();
    }

    public static function getExistingByPhone(string $phoneNumber): self
    {
        $mobileAccount = MobileAccountModel::where(['phone_number' => $phoneNumber])->firstOrFail();

        return new self($mobileAccount);
    }

    public function isValidPincode(string $pincode): bool
    {
        return $this->mobileAccount->pincode === $pincode;
    }

    public function getUserModelId(): int
    {
        return $this->mobileAccount->user_id;
    }
}
