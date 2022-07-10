<?php

namespace App\Module\Customer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $phone_number
 */
class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone_number',
    ];
}
