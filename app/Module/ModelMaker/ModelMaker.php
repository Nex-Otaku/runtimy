<?php

namespace App\Module\ModelMaker;

class ModelMaker
{
    private string $modelNameCamelCased = '';
    private string $modelNameSnakeCased = '';
    private string $tableName = '';

    public static function instance(): self
    {
        return new self();
    }

    public function getCommands(): array
    {
        return [
            "php artisan make:migration create_{$this->tableName}_table --create={$this->tableName}",
            "php artisan migrate",
            "php artisan make:model {$this->modelNameCamelCased}",
        ];
    }

    private function parseName(string $modelNameWithSpaces): void
    {
        $words = explode(' ', trim($modelNameWithSpaces));
        $filtered = [];

        foreach ($words as $word) {
            if (trim($word) === '') {
                continue;
            }

            $filtered []= $word;
        }

        $this->modelNameCamelCased = implode('', array_map(function (string $value) {
            return $this->camelize($value);
        }, $filtered));

        $this->modelNameSnakeCased = implode('_', array_map('strtolower', $filtered));

        $this->tableName = $this->pluralize($this->modelNameSnakeCased);
    }

    public function make(string $modelNameWithSpaces): void
    {
        $this->parseName($modelNameWithSpaces);
        $this->echoPrint($this->getCommands());
    }

    private function camelize(string $value): string
    {
        return strtoupper(substr($value, 0, 1))
            . strtolower(substr($value, 1));
    }

    private function lowercase(string $value): string
    {
        return strtoupper(substr($value, 0, 1))
            . strtolower(substr($value, 1));
    }

    private function echoPrint(array $lines): void
    {
        foreach ($lines as $line) {
            echo $line . "\n";
        }
    }

    private function pluralize(string $value): string
    {
        if (strlen($value) === 0) {
            return '';
        }

        $lastLetter = substr($value, -1, 1);

        if ($this->isConsonant($lastLetter)) {
            return $value . 'es';
        }

        return $value . 's';
    }

    private function isConsonant(string $letter): bool
    {
        if ($letter === '') {
            return false;
        }

        return str_contains('bcdfghjklmnpqrstvwxz', $letter);
    }
}
