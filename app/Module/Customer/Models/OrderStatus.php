<?php

namespace App\Module\Customer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $order_id
 * @property int|null $next_place_id
 * @property string $phase
 * @property int $is_active
 */
class OrderStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'next_place_id',
        'phase',
        'is_active',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
