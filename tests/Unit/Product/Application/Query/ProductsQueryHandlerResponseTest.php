<?php

namespace Tests\Unit\Product\Application\Query;

use App\Product\Application\Dto\ProductDto;
use App\Product\Application\Query\ProductsQueryHandlerResponse;
use PHPUnit\Framework\TestCase;

class ProductsQueryHandlerResponseTest extends TestCase
{
    public function testCanCreateProductsQueryHandlerResponse(): void
    {
        $sku = '000001';
        $name = 'dummy product name';
        $category = 'dummy category name';
        $originalPrice = 1111;
        $finalPrice = 2222;
        $discountPercentage = 30;
        $currency = 'EUR';

        $dto = new ProductDto(
            $sku,
            $name,
            $category,
            $originalPrice,
            $finalPrice,
            $discountPercentage,
            $currency
        );

        $sut = new ProductsQueryHandlerResponse();
        $sut->appendProduct($dto);

        $this->assertIsArray($sut->getProducts());
        $this->assertSame($dto, $sut->getProducts()[0]);
    }
}
