<?php

namespace App\Product\Application\Transformer;

use App\Product\Application\Dto\ProductDto;
use App\Product\Application\Query\ProductsQueryHandlerResponse;

class ProductsJsonTransformer implements ProductsTransformerInterface
{
    public const FIELD_SKU = 'sku';
    public const FIELD_NAME = 'name';
    public const FIELD_CATEGORY = 'category';
    public const FIELD_PRICE = 'price';
    public const FIELD_PRICE_ORIGINAL = 'original';
    public const FIELD_PRICE_FINAL = 'final';
    public const FIELD_PRICE_DISCOUNT = 'discount_percentage';
    public const FIELD_PRICE_CURRENCY = 'currency';

    public function transform(ProductsQueryHandlerResponse $response): array
    {
        $products = [];

        /** @var ProductDto $product */
        foreach ($response->getProducts() as $product) {
            $products[] = [
                self::FIELD_SKU => $product->getSku(),
                self::FIELD_NAME => $product->getName(),
                self::FIELD_CATEGORY => $product->getCategory(),
                self::FIELD_PRICE => [
                    self::FIELD_PRICE_ORIGINAL => $product->getOriginalPrice(),
                    self::FIELD_PRICE_FINAL => $product->getFinalPrice(),
                    self::FIELD_PRICE_DISCOUNT => $product->getDiscountPercentage() . '%',
                    self::FIELD_PRICE_CURRENCY => $product->getCurrency(),
                ]
            ];
        }

        return $products;
    }
}
