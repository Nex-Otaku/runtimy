<?php

namespace App\Module\MobileAuth\Entities;

use App\Models\User;
use App\Module\Common\Environment;
use App\Module\Common\PhoneNumber;
use App\Module\MobileAuth\Models\MobileAccount as MobileAccountModel;
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

    public static function register(PhoneNumber $phoneNumber): self
    {
        $user = new User();
        $user->saveOrFail();

        $mobileAccount = new MobileAccountModel(
            [
                'user_id' => $user->id,
                'pincode' => null,
                'phone_number' => $phoneNumber->asDbValue(),
            ]
        );

        $mobileAccount->saveOrFail();

        return new self($mobileAccount);
    }

    public static function createDemo(): self
    {
        return self::register(PhoneNumber::fromInputString('+71112223344'));
    }

    public static function existsByPhone(PhoneNumber $phoneNumber): bool
    {
        return MobileAccountModel::where(['phone_number' => $phoneNumber->asDbValue()])->exists();
    }

    public static function getExistingById(int $id): self
    {
        $mobileAccount = MobileAccountModel::where(['id' => $id])->firstOrFail();

        return new self($mobileAccount);
    }

    public function sendPincode(): void
    {
        if (Environment::get()->isLocal()) {
            $pincode = 1111;
        } else {
            $pincode = (new PincodeSender())->sendPincode($this->getPhoneNumber());
        }

        $this->mobileAccount->pincode = $pincode;
        $this->mobileAccount->saveOrFail();
    }

    public static function getExistingByPhone(PhoneNumber $phoneNumber): self
    {
        $mobileAccount = MobileAccountModel::where(['phone_number' => $phoneNumber->asDbValue()])->firstOrFail();

        return new self($mobileAccount);
    }

    public function isValidPincode(string $pincode): bool
    {
        return $this->mobileAccount->pincode === $pincode;
    }

    public function getModelId(): int
    {
        return $this->mobileAccount->id;
    }

    public function getModelUserId(): int
    {
        return $this->mobileAccount->user_id;
    }

    private function getPhoneNumber(): PhoneNumber
    {
        return PhoneNumber::fromDb($this->mobileAccount->phone_number);
    }

    public function clearPincode(): void
    {
        $this->mobileAccount->pincode = null;
        $this->mobileAccount->saveOrFail();
    }
}
