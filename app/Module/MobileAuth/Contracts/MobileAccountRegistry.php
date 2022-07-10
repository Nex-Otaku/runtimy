<?php

namespace App\Module\MobileAuth\Contracts;

use App\Module\Common\PhoneNumber;

interface MobileAccountRegistry
{
    public function registerCustomerMobileAccount(PhoneNumber $phoneNumber): UserId;
}
