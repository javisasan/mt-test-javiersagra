<?php

namespace Tests\Unit\Product\Domain\ValueObject;

use App\Product\Domain\Exception\InvalidCurrencyException;
use App\Product\Domain\ValueObject\Currency;
use PHPUnit\Framework\TestCase;

class CurrencyTest extends TestCase
{
    public function testCanCreateEuroCurrency(): void
    {
        $code = Currency::EURO;

        $sut = Currency::create($code);

        $this->assertSame($code, $sut->getCode());
    }

    public function testCanCreateDollarCurrency(): void
    {
        $code = Currency::DOLLAR;

        $sut = Currency::create($code);

        $this->assertSame($code, $sut->getCode());
    }

    public function testCanNotCreateInvalidCurrency(): void
    {
        $this->expectException(InvalidCurrencyException::class);

        $code = 'dummy-currency';

        Currency::create($code);
    }
}
