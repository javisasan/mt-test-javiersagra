<?php

namespace Tests\Unit\Product\Application\Query;

use App\Product\Application\Dto\ProductDto;
use App\Product\Application\Query\ProductsQueryHandlerResponse;
use App\Product\Application\Transformer\ProductsJsonTransformer;
use PHPUnit\Framework\TestCase;

class ProductsJsonTransformerTest extends TestCase
{
    public function testCanCreateProductTransformer(): void
    {
        $sku = '000001';
        $name = 'dummy product name';
        $category = 'dummy category name';
        $originalPrice = 1111;
        $finalPrice = 2222;
        $discountPercentage = 30;
        $currency = 'EUR';

        $expected = [
            [
                ProductsJsonTransformer::FIELD_SKU => $sku,
                ProductsJsonTransformer::FIELD_NAME => $name,
                ProductsJsonTransformer::FIELD_CATEGORY => $category,
                ProductsJsonTransformer::FIELD_PRICE => [
                    ProductsJsonTransformer::FIELD_PRICE_ORIGINAL => $originalPrice,
                    ProductsJsonTransformer::FIELD_PRICE_FINAL => $finalPrice,
                    ProductsJsonTransformer::FIELD_PRICE_DISCOUNT => $discountPercentage . '%',
                    ProductsJsonTransformer::FIELD_PRICE_CURRENCY => $currency,
                ]
            ]
        ];

        $dto = new ProductDto(
            $sku,
            $name,
            $category,
            $originalPrice,
            $finalPrice,
            $discountPercentage,
            $currency
        );

        $response = new ProductsQueryHandlerResponse();
        $response->appendProduct($dto);

        $sut = new ProductsJsonTransformer();
        $result = $sut->transform($response);

        $this->assertEquals($expected, $result);
    }
}
