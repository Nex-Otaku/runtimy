<?php

namespace App\Module\Customer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string|null $avatar_url
 * @property string $phone_number
 */
class Courier extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'avatar_url',
        'phone_number',
    ];
}
