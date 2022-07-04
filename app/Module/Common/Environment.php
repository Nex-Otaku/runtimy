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

    public static function get(): self
    {
        $mode = env('APP_ENVIRONMENT_MODE', self::MODE_LOCAL);

        if ($mode === self::MODE_PRODUCTION) {
            return new self(self::MODE_LOCAL);
        }

        if ($mode === self::MODE_LOCAL) {
            return new self(self::MODE_LOCAL);
        }

        throw new \InvalidArgumentException('Неизвестный режим запуска: ' . $mode);
    }

    public function isProduction(): bool
    {
        return $this->mode === self::MODE_PRODUCTION;
    }

    public function isLocal(): bool
    {
        return $this->mode === self::MODE_LOCAL;
    }
}
