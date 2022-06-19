<?php

namespace App\Module\Payment\Entities;

use App\Module\Common\Json;
use App\Module\Common\Money;
use App\Module\Payment\Models\YookassaPayment as YookassaPaymentModel;
use YooKassa\Model\PaymentInterface;
use YooKassa\Model\RefundInterface;
use YooKassa\Request\Payments\CreatePaymentResponse;

class YookassaPayment
{
    private const STATUS_PENDING             = 'pending';
    private const STATUS_WAITING_FOR_CAPTURE = 'waiting_for_capture';
    private const STATUS_SUCCEEDED           = 'succeeded';
    private const STATUS_CANCELED            = 'canceled';

    private YookassaPaymentModel $yookassaPayment;

    private function __construct(
        YookassaPaymentModel $yookassaPayment
    ) {
        $this->yookassaPayment = $yookassaPayment;
    }

    public static function create(
        int                   $shopId,
        int                   $paymentId,
        CreatePaymentResponse $paymentResponse,
        string                $returnUrl
    ): self {
        $metadata = $paymentResponse->metadata !== null
            ? Json::encode($paymentResponse->metadata->toArray())
            : null;

        $yookassaPayment = new YookassaPaymentModel(
            [
                'payment_id' => $paymentId,
                'shop_id' => $shopId,
                'recipient_account_id' => $paymentResponse->recipient->accountId,
                'recipient_gateway_id' => $paymentResponse->recipient->gatewayId,
                'payment_method_type' => $paymentResponse->paymentMethod?->getType(),
                'payment_method_title' => $paymentResponse->paymentMethod?->title,
                'amount' => $paymentResponse->amount->value,
                'currency' => $paymentResponse->amount->currency,
                'income_amount' => $paymentResponse->incomeAmount?->value,
                'income_currency' => $paymentResponse->incomeAmount?->currency,
                'confirmation_type' => $paymentResponse->confirmation->type,
                'return_url' => $returnUrl,
                'confirmation_url' => $paymentResponse->confirmation->getConfirmationUrl(),
                'description' => $paymentResponse->description,
                'external_id' => $paymentResponse->id,
                'status' => $paymentResponse->status,
                'paid' => $paymentResponse->paid ? 1 : 0,
                'refundable' => $paymentResponse->refundable ? 1 : 0,
                'refunded_amount' => $paymentResponse->refundedAmount?->value,
                'refunded_currency' => $paymentResponse->refundedAmount?->currency,
                'test' => $paymentResponse->test ? 1 : 0,
                'captured_at' => $paymentResponse->capturedAt,
                'created_at' => $paymentResponse->createdAt,
                'expires_at' => $paymentResponse->expiresAt,
                'receipt_registration' => $paymentResponse->receiptRegistration,
                'metadata' => $metadata,
                'cancellation_party' => $paymentResponse->cancellationDetails?->party,
                'cancellation_reason' => $paymentResponse->cancellationDetails?->reason,
                'error' => '',
            ]
        );

        $yookassaPayment->saveOrFail();

        return new self($yookassaPayment);
    }

    public static function getByExternalId(string $externalId): self
    {
        $model = YookassaPaymentModel::where(
            [
                'external_id' => $externalId,
            ]
        )->firstOrFail();

        return new self($model);
    }

    public static function findByMainPaymentId(int $mainPaymentId): ?self
    {
        $model = YookassaPaymentModel::where(
            [
                'payment_id' => $mainPaymentId,
            ]
        )->first();

        if ($model === null) {
            return null;
        }

        return new self($model);
    }

    public function getModelId(): int
    {
        return $this->yookassaPayment->id;
    }

    public function canSucceed(): bool
    {
        return in_array(
            $this->yookassaPayment->status,
            [
                self::STATUS_PENDING,
                self::STATUS_WAITING_FOR_CAPTURE,
            ]
        );
    }

    public function getMainPayment(): Payment
    {
        return Payment::getById($this->yookassaPayment->payment_id);
    }

    public function complete(PaymentInterface $paymentInfo): void
    {
        $this->yookassaPayment->status = self::STATUS_SUCCEEDED;
        $this->yookassaPayment->income_amount = $paymentInfo->incomeAmount?->value;
        $this->yookassaPayment->income_currency = $paymentInfo->incomeAmount?->currency;
        $this->yookassaPayment->payment_method_type = $paymentInfo->paymentMethod?->getType();
        $this->yookassaPayment->payment_method_title = $paymentInfo->paymentMethod?->title;
        $this->yookassaPayment->paid = $paymentInfo->paid ? 1 : 0;
        $this->yookassaPayment->refundable = $paymentInfo->refundable ? 1 : 0;
        $this->yookassaPayment->captured_at = $paymentInfo->capturedAt;
        $this->yookassaPayment->expires_at = $paymentInfo->expiresAt;
        $this->yookassaPayment->receipt_registration = $paymentInfo->receiptRegistration;
        $this->yookassaPayment->saveOrFail();
    }

    public function canConfirm(): bool
    {
        return $this->yookassaPayment->status === self::STATUS_PENDING;
    }

    public function getAmount(): Money
    {
        return Money::createFromString($this->yookassaPayment->amount);
    }

    public function canCancel(): bool
    {
        return in_array(
            $this->yookassaPayment->status,
            [
                self::STATUS_PENDING,
                self::STATUS_WAITING_FOR_CAPTURE,
            ]
        );
    }

    public function cancel(PaymentInterface $paymentInfo): void
    {
        $this->yookassaPayment->cancellation_party = $paymentInfo->cancellationDetails?->party;
        $this->yookassaPayment->cancellation_reason = $paymentInfo->cancellationDetails?->reason;
        $this->yookassaPayment->status = self::STATUS_CANCELED;
        $this->yookassaPayment->saveOrFail();
    }

    public function canRefund(): bool
    {
        return
            $this->yookassaPayment->status === self::STATUS_SUCCEEDED
            && $this->yookassaPayment->refundable === 1
            && $this->yookassaPayment->paid === 1
            && (
                ($this->yookassaPayment->refunded_amount === null)
                || (Money::createFromString($this->yookassaPayment->refunded_amount)->isZero())
            );
    }

    public function refund(RefundInterface $refundInfo): void
    {
        $this->yookassaPayment->refunded_amount = $refundInfo->amount?->value;
        $this->yookassaPayment->refunded_currency = $refundInfo->amount?->currency;
        $this->yookassaPayment->saveOrFail();
    }

    public function getConfirmationUrl(): ?string
    {
        return $this->yookassaPayment->confirmation_url;
    }
}
