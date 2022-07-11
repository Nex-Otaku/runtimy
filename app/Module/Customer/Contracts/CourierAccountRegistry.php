<?php

namespace App\Module\Customer\Contracts;

interface CourierAccountRegistry
{
    public function registerCourier(): CourierAccount;
    public function removeCourier(CourierAccount $courierAccount): void;
}
