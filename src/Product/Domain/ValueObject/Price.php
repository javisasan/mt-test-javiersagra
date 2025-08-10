<?php

namespace App\Product\Domain\ValueObject;

class Price
{
    private function __construct(
        private int $value,
        private Currency $currency
    ) {
    }

    public static function create(
        int $value,
        Currency $currency
    ) {
        return new self(
            $value,
            $currency
        );
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }
}
