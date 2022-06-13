<?php

namespace App\Module\Payment\Contracts;

use App\Module\Common\Money;

interface PaymentOrder
{
    public function getModelId(): int;
    public function getCustomerId(): int;
    public function getAmount(): Money;
}
