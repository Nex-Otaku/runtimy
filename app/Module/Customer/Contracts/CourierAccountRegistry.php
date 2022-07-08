<?php

namespace App\Module\Customer\Contracts;

interface CourierAccountRegistry
{
    public function registerCourier(): CourierAccount;
}
