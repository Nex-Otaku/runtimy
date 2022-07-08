<?php

namespace App\Module\Admin\Vo;

class Role
{
    private const ROLE_CUSTOMER = 'customer';
    private const ROLE_COURIER = 'courier';
    private const ROLE_OPERATOR = 'operator';
    private const ROLE_DEMO = 'demo';

    private const ROLES = [
        self::ROLE_CUSTOMER,
        self::ROLE_COURIER,
        self::ROLE_OPERATOR,
        self::ROLE_DEMO,
    ];

    private const LK_ROLES = [
        self::ROLE_OPERATOR,
        self::ROLE_DEMO,
    ];

    private string $role;

    private function __construct(string $role)
    {
        if (!in_array($role, self::ROLES)) {
            throw new \LogicException('Неизвестная роль: ' . $role);
        }

        $this->role = $role;
    }

    public static function getLkChoices(): array
    {
        return self::LK_ROLES;
    }

    public static function fromString(string $role): self
    {
        return new self($role);
    }

    public static function customer(): self
    {
        return new self(self::ROLE_CUSTOMER);
    }

    public static function courier(): self
    {
        return new self(self::ROLE_COURIER);
    }

    public static function demo(): self
    {
        return new self(self::ROLE_DEMO);
    }

    public function toString(): string
    {
        return $this->role;
    }

    public function isAllowedLk(): bool
    {
        return in_array($this->role, self::LK_ROLES);
    }
}
