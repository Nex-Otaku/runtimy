<?php

namespace App\Module\Customer\Entities;

class Customer
{
    private int $spaUserId;

    private function __construct(
        int $spaUserId
    ) {
        $this->spaUserId = $spaUserId;
    }

    public static function takeLogined(): self
    {
        return new self(1);
    }

    public function getSpaUserId(): int
    {
        return $this->spaUserId;
    }
}
