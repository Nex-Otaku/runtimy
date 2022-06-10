<?php

namespace App\Module\Payment\Entities;

use App\Module\Payment\Contracts\PaymentOrder;

class Payment
{
    public static function create(PaymentOrder $paymentOrder): self
    {
        // TODO
        return new self();
    }

    public function getPayUrl(): string
    {
        // TODO
        return '';
    }
}
