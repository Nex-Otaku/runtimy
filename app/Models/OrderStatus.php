<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $order_id
 * @property int|null $next_place_id
 * @property string $phase
 */
class OrderStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'next_place_id',
        'phase',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
