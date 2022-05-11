<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $transport_type
 * @property string $size_type
 * @property string $weight_type
 * @property string $description
 * @property int|null $price_of_package
 */
class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'transport_type',
        'size_type',
        'weight_type',
        'description',
        'price_of_package',
    ];
}
