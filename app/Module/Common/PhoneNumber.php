<?php

namespace App\Module\Common;

class PhoneNumber
{
    /** @var string */
    private $value;

    private function __construct(
        string $value
    )
    {
        $this->value = $this->normalize($value);
    }

    public static function fromInputString(string $value): self
    {
        return new self('7' . $value);
    }

    public static function fromFakerString(string $value): self
    {
        return new self($value);
    }

    public static function fromDb(string $value): self
    {
        return new self($value);
    }

    private function normalize(string $value): string
    {
        return preg_replace('/[^0-9]/', '', $value);
    }

    public function asDbValue(): string
    {
        return $this->value;
    }

    public function asUri(): string
    {
        return '+' . $this->value;
    }

    public function asSmsRuFormat(): string
    {
        return $this->value;
    }

    public function asSmsPilotFormat(): string
    {
        return $this->value;
    }

    public function asApiFormat(): string
    {
        return $this->value;
    }
}
