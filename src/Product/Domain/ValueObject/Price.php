<?php

namespace App\Product\Domain\ValueObject;

use App\Product\Domain\Exception\InvalidPriceException;

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
        if ($value <= 0) {
            throw new InvalidPriceException();
        }

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
