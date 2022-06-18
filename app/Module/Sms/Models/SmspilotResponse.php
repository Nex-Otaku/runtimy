<?php

namespace App\Module\Sms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property int    $sms_request_id
 * @property int    $server_id
 * @property string $phone
 * @property string $price
 * @property string $status
 * @property string $balance
 * @property string $cost
 * @property int    $server_packet_id
 */
class SmspilotResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'sms_request_id',
        'server_id',
        'phone',
        'price',
        'status',
        'balance',
        'cost',
        'server_packet_id',
    ];
}
