<?php

namespace Tests\Unit\Product\Domain\ValueObject;

use App\Product\Domain\Exception\InvalidPriceException;
use App\Product\Domain\ValueObject\Currency;
use App\Product\Domain\ValueObject\Price;
use PHPUnit\Framework\TestCase;

class PriceTest extends TestCase
{
    public function testCanCreatePrice(): void
    {
        $value = 11000;
        $currency = Currency::create(Currency::EURO);

        $sut = Price::create($value, $currency);

        $this->assertSame($value, $sut->getValue());
        $this->assertSame($currency, $sut->getCurrency());
    }

    public function testCanNotCreatePriceWithNegativeValue(): void
    {
        $this->expectException(InvalidPriceException::class);

        $value = -11000;
        $currency = Currency::create(Currency::EURO);

        Price::create($value, $currency);
    }
}
