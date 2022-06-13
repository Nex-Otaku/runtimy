<?php

namespace App\Module\Payment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $order_id
 * @property int $user_id
 * @property string $amount
 * @property string $status
 * @property string $error
 */
class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'amount',
        'status',
        'error',
    ];
}
