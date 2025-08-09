<?php

namespace App\Product\Domain\Entity;

class Product
{
    private function __construct(
        private string $sku,
        private string $name,
        private Category $category,
        private int $price
    ) {
    }

    public static function create(
        string $sku,
        string $name,
        Category $category,
        int $price
    ) {
        return new self(
            $sku,
            $name,
            $category,
            $price
        );
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    /*
    public function getPriceWithDiscountPercent(int $discountPercent): int
    {
        $discount = $this->price * ($discountPercent / 100);

        return $this->price - $discount;
    }
     */
}
