<?php

namespace App\Nova\Contracts;

interface NovaUserRegistry
{
    public function isAllowedNovaUser(int $userId): bool;
}
