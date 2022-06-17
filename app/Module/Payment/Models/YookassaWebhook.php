<?php

namespace App\Module\Payment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $request_content
 * @property string $request_headers
 * @property string $status
 * @property string $error
 */
class YookassaWebhook extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_content',
        'request_headers',
        'status',
        'error',
    ];
}
