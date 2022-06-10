<?php

namespace App\Module\Payment\Contracts;

interface PaymentOrder
{
    public function getModelId(): int;
}
