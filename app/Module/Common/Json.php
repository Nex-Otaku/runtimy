<?php

namespace App\Module\Common;

class Json
{
    public static function decode(string $json): ?array
    {
        try {
            $value = json_decode($json, true);
        } catch (\Throwable $throwable) {
            return null;
        }

        return $value;
    }

    public static function encode(array $value): string
    {
        return json_encode($value);
    }
}
