<?php

namespace App\Module\Payment\Entities;

use App\Module\Common\Money;
use App\Module\Payment\Contracts\PaymentOrder;
use App\Module\Payment\Models\Payment as PaymentModel;

class Payment
{
    private const STATUS_DRAFT    = 'draft';
    private const STATUS_PAID     = 'paid';
    private const STATUS_CANCELED = 'canceled';
    private const STATUS_FAILED    = 'failed';
    private const STATUS_REFUND    = 'refund';

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
                                        'amount' => $paymentOrder->getAmount()->toString(),
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

    public function fail(string $error): void
    {
        $this->payment->error = $error;
        $this->payment->status = self::STATUS_FAILED;
        $this->payment->saveOrFail();
    }

    public function getDescription(): string
    {
        return "Заказ №{$this->payment->order_id}";
    }

    public function getById(int $id): self
    {
        $model = PaymentModel::where(
            [
                'id' => $id,
            ]
        )->firstOrFail();

        return new self($model);
    }

    public function complete(): void
    {
        $this->payment->status = self::STATUS_PAID;
        $this->payment->saveOrFail();
    }

    public function cancel(): void
    {
        $this->payment->status = self::STATUS_CANCELED;
        $this->payment->saveOrFail();
    }

    public function refund(): void
    {
        $this->payment->status = self::STATUS_REFUND;
        $this->payment->saveOrFail();
    }
}
