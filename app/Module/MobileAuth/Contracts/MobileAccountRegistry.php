<?php

namespace App\Module\MobileAuth\Contracts;

interface MobileAccountRegistry
{
    public function registerCustomerMobileAccount(): UserId;
}
