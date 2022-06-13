<?php

namespace App\Module\Payment\Entities;

use App\Module\Common\Money;
use App\Module\Payment\Contracts\PaymentOrder;

class FakeOrder implements PaymentOrder
{
    public function getModelId(): int
    {
        return 100500;
    }

    public function getCustomerId(): int
    {
        return 1000;
    }

    public function getAmount(): Money
    {
        return Money::createFromString('8888');
    }
}
