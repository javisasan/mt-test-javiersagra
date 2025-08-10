<?php

namespace Tests\Unit\Product\Application\Dto;

use App\Product\Application\Dto\ProductDto;
use PHPUnit\Framework\TestCase;

class ProductDtoTest extends TestCase
{
    public function testCanCreateProductDto(): void
    {
        $sku = '000001';
        $name = 'dummy product name';
        $category = 'dummy category name';
        $originalPrice = 1111;
        $finalPrice = 2222;
        $discountPercentage = 30;
        $currency = 'EUR';

        $sut = new ProductDto(
            $sku,
            $name,
            $category,
            $originalPrice,
            $finalPrice,
            $discountPercentage,
            $currency
        );

        $this->assertSame($sku, $sut->getSku());
        $this->assertSame($name, $sut->getName());
        $this->assertSame($category, $sut->getCategory());
        $this->assertSame($originalPrice, $sut->getOriginalPrice());
        $this->assertSame($finalPrice, $sut->getFinalPrice());
        $this->assertSame($discountPercentage, $sut->getDiscountPercentage());
        $this->assertSame($currency, $sut->getCurrency());
    }
}
