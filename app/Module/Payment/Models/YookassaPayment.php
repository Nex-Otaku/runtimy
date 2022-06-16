<?php

namespace App\Module\Payment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $payment_id
 * @property int $shop_id
 * @property string $recipient_account_id
 * @property string $recipient_gateway_id
 * @property string $payment_method_type
 * @property string $payment_method_title
 * @property string $amount
 * @property string $currency
 * @property string $income_amount
 * @property string $income_currency
 * @property string $confirmation_type
 * @property string $description
 * @property string $external_id
 * @property string $status
 * @property int $paid
 * @property int $refundable
 * @property string $refunded_amount
 * @property string $refunded_currency
 * @property int $test
 * @property \DateTimeInterface|null $captured_at
 * @property \DateTimeInterface|null $created_at
 * @property \DateTimeInterface|null $expires_at
 * @property string $receipt_registration
 * @property string $metadata
 * @property string $cancellation_party
 * @property string $cancellation_reason
 * @property string $error
 */
class YookassaPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'shop_id',
        'recipient_account_id',
        'recipient_gateway_id',
        'payment_method_type',
        'payment_method_title',
        'amount',
        'currency',
        'income_amount',
        'income_currency',
        'confirmation_type',
        'description',
        'external_id',
        'status',
        'paid',
        'refundable',
        'refunded_amount',
        'refunded_currency',
        'test',
        'captured_at',
        'created_at',
        'expires_at',
        'receipt_registration',
        'metadata',
        'cancellation_party',
        'cancellation_reason',
        'error',
    ];
}
