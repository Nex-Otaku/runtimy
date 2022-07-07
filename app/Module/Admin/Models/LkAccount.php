<?php

namespace App\Module\Admin\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $role
 */
class LkAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'role',
    ];
}
