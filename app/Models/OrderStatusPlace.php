<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $order_status_id
 * @property int $order_place_id
 * @property int $is_estimated_coming_time
 * @property int|string $will_come_from
 * @property int|string $will_come_to
 */
class OrderStatusPlace extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_status_id',
        'order_place_id',
        'is_estimated_coming_time',
        'will_come_from',
        'will_come_to',
    ];
}
