<?php

namespace App\Module\Customer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $customer_id
 * @property int|null $assigned_courier_id
 * @property string $transport_type
 * @property string $size_type
 * @property string $weight_type
 * @property string $description
 * @property int|null $price_of_package
 * @property \DateTimeInterface $created_at
 */
class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'assigned_courier_id',
        'transport_type',
        'size_type',
        'weight_type',
        'description',
        'price_of_package',
    ];
}
