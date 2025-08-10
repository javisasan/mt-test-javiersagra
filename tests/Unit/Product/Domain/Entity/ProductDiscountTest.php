<?php

namespace Tests\Unit\Product\Domain\Entity;

use App\Product\Domain\Entity\ProductDiscount;
use App\Product\Domain\Exception\InvalidDiscountParametersException;
use PHPUnit\Framework\TestCase;

class ProductDiscountTest extends TestCase
{
    public function testCanCreateProductDiscount(): void
    {
        $sku = '000001';
        $discount = 30;

        $sut = ProductDiscount::create($sku, $discount);

        $this->assertSame($sku, $sut->getProductSku());
        $this->assertSame($discount, $sut->getDiscount());
    }

    public function testCanNotCreateProductDiscountByEmptySku(): void
    {
        $this->expectException(InvalidDiscountParametersException::class);

        $sku = '';
        $discount = 30;

        ProductDiscount::create($sku, $discount);
    }

    public function testCanNotCreateProductDiscountByLowerDiscount(): void
    {
        $this->expectException(InvalidDiscountParametersException::class);

        $sku = '000001';
        $discount = -1;

        ProductDiscount::create($sku, $discount);
    }

    public function testCanNotCreateProductDiscountByHigherDiscount(): void
    {
        $this->expectException(InvalidDiscountParametersException::class);

        $sku = '000001';
        $discount = 101;

        ProductDiscount::create($sku, $discount);
    }
}
