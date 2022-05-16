<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $order_id
 * @property int $sort_index
 * @property string $street_address
 * @property string $phone_number
 * @property string $courier_comment
 */
class OrderPlace extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'sort_index',
        'street_address',
        'phone_number',
        'courier_comment',
    ];
}
