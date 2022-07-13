<?php

namespace App\Module\Common;

use App\Module\Admin\LkAccountRegistry;
use App\Module\Customer\Contracts\CourierAccountRegistry;
use App\Module\MobileAuth\Contracts\MobileAccountRegistry;
use App\Nova\Contracts\NovaUserRegistry;

class ModuleSystem
{
    private function __construct()
    {
    }

    public static function instance(): self
    {
        return new self();
    }

    public function getMobileAccountRegistry(): MobileAccountRegistry
    {
        return LkAccountRegistry::instance();
    }

    public function getNovaUserRegistry(): NovaUserRegistry
    {
        return LkAccountRegistry::instance();
    }

    public function getCourierAccountRegistry(): CourierAccountRegistry
    {
        return LkAccountRegistry::instance();
    }
}
