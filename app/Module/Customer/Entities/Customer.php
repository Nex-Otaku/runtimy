<?php

namespace App\Module\Customer\Entities;

use Illuminate\Support\Facades\Auth;

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
        if (!Auth::guard('mobile')->check()) {
            throw new \LogicException('Пользователь не авторизован. Проверьте откуда вызывается метод');
        }

        return new self(Auth::guard('mobile')->id());
    }

    public function getSpaUserId(): int
    {
        return $this->spaUserId;
    }
}
