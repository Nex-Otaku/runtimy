<?php

namespace App\Module\MobileAuth;

use App\Module\Common\PhoneNumber;
use App\Module\Sms\SmsPilot;

class PincodeSender
{
    public function sendPincode(PhoneNumber $phoneNumber): string
    {
        $pincode = random_int(1000, 9999);
        SmsPilot::makeFromConfig()->sendSms($phoneNumber, 'Ваш код ' . $pincode);

        return $pincode;
    }
}
