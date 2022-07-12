<?php

namespace App\Module\Customer\Entities;

use App\Module\MobileAuth\Entities\MobileAccount;
use Illuminate\Support\Facades\Auth;
use App\Module\Customer\Models\Customer as CustomerModel;

class Customer
{
    private int $spaUserId;

    private function __construct(
        int $spaUserId
    ) {
        $this->spaUserId = $spaUserId;
    }

    public static function takeLogined(): self
    {
        if (!Auth::guard('mobile')->check()) {
            throw new \LogicException('Пользователь не авторизован. Проверьте откуда вызывается метод');
        }

        $mobileAccount = MobileAccount::getExistingById(Auth::guard('mobile')->id());

        return new self($mobileAccount->getModelUserId());
    }

    public function getCustomerModelId(): int
    {
        $customer = CustomerModel::where(
            [
                'user_id' => $this->spaUserId,
            ]
        )->firstOrFail();

        return $customer->id;
    }
}
