<?php

namespace App\Module\Payment\Entities;

use App\Module\Common\Json;
use App\Module\Payment\Models\YookassaPayment as YookassaPaymentModel;
use YooKassa\Request\Payments\CreatePaymentResponse;

class YookassaPayment
{
    private YookassaPaymentModel $yookassaPayment;

    private function __construct(
        YookassaPaymentModel $yookassaPayment
    ) {
        $this->yookassaPayment = $yookassaPayment;
    }

    public static function create(int $shopId, int $paymentId, CreatePaymentResponse $paymentResponse): self
    {
        $metadata = $paymentResponse->metadata !== null
                ? Json::encode($paymentResponse->metadata->toArray())
                : null;

        $yookassaPayment = new YookassaPaymentModel([
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
                                                    ]);

        $yookassaPayment->saveOrFail();

        return new self($yookassaPayment);
    }
}
