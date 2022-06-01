<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string|null $pincode
 * @property string $phone_number
 */
class MobileAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pincode',
        'phone_number',
    ];
}
