<?php

namespace App\Module\Payment\Entities;

use App\Module\Common\Money;
use App\Module\Payment\Contracts\PaymentOrder;
use App\Module\Payment\Models\Payment as PaymentModel;

class Payment
{
    private const STATUS_DRAFT     = 'draft';
    private const STATUS_COMPLETED = 'completed';
    private const STATUS_CANCELED  = 'canceled';
    private const STATUS_FAILED    = 'failed';

    private PaymentModel $payment;

    private function __construct(
        PaymentModel $payment
    ) {
        $this->payment = $payment;
    }

    public static function create(PaymentOrder $paymentOrder): self
    {
        $payment = new PaymentModel([
                                        'order_id' => $paymentOrder->getModelId(),
                                        'user_id' => $paymentOrder->getCustomerId(),
                                        'amount' => $paymentOrder->getAmount(),
                                        'status' => self::STATUS_DRAFT,
                                        'error' => '',
                                    ]);

        $payment->saveOrFail();

        return new self($payment);
    }

    public function getAmount(): Money
    {
        return Money::createFromString($this->payment->amount);
    }

    public function getModelId(): int
    {
        return $this->payment->id;
    }
}
