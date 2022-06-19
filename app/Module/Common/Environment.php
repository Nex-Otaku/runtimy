<?php

namespace App\Module\Common;

class Environment
{
    private const MODE_PRODUCTION = 'production';
    private const MODE_LOCAL = 'local';

    private string $mode;

    private function __construct(
        string $mode
    )
    {
        $this->mode = $mode;
    }

    public static function makeLocal(): self
    {
        return new self(self::MODE_LOCAL);
    }

    public static function get(): self
    {
        return self::makeLocal();
    }

    public function isProduction(): bool
    {
        return $this->mode === self::MODE_PRODUCTION;
    }
}
