<?php

namespace App\Product\Domain\Entity;

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
