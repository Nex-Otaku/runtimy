<?php

namespace App\Module\Common;

use App\Module\Admin\LkAccountRegistry;
use App\Module\MobileAuth\Contracts\MobileAccountRegistry;

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
}
