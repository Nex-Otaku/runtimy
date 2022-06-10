<?php

namespace App\Module\Payment\Entities;

use App\Module\Payment\Contracts\PaymentOrder;

class FakeOrder implements PaymentOrder
{
    public function getModelId(): int
    {
        return 100500;
    }
}
