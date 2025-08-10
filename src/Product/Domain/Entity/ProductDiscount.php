<?php

namespace App\Product\Domain\Entity;

use App\Product\Domain\Exception\InvalidDiscountParametersException;

class ProductDiscount extends AbstractDiscount
{
    private function __construct(
        private string $productSku,
        protected int $discount
    ) {
    }

    public static function create(
        string $productSku,
        int $discount
    ) {
        if (empty($productSku) || $discount < 0 || $discount > 100) {
            throw new InvalidDiscountParametersException();
        }

        return new self(
            $productSku,
            $discount
        );
    }

    public function getProductSku(): string
    {
        return $this->productSku;
    }
}
