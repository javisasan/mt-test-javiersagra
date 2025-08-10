<?php

namespace App\Product\Domain\ValueObject;

use App\Product\Domain\Exception\InvalidCurrencyException;

class Currency
{
    public const EURO = 'EUR';
    public const DOLLAR = 'USD';

    public const VALID_CURRENCIES = [
        self::EURO,
        self::DOLLAR
    ];

    private function __construct(
        private string $code
    ) {
    }

    public static function create(
        string $code
    ) {
        if (!in_array($code, self::VALID_CURRENCIES)) {
            throw new InvalidCurrencyException();
        }

        return new self(
            $code
        );
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
