<?php

namespace App\Module\Sms;

use App\Module\Common\PhoneNumber;

interface SmsSender
{
    public function sendSms(PhoneNumber $phoneNumber, string $message): void;
}
