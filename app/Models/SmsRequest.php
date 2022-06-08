<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property string $message
 * @property string $phone_number
 * @property string $status
 * @property string $error
 */
class SmsRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'phone_number',
        'status',
        'error',
    ];
}
