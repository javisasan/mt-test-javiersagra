<?php

namespace App\Product\Domain\Entity;

use App\Product\Domain\Exception\InvalidProductNameException;
use App\Product\Domain\Exception\InvalidProductSkuException;
use App\Product\Domain\ValueObject\Currency;
use App\Product\Domain\ValueObject\Price;

class Product
{
    private function __construct(
        private string $sku,
        private string $name,
        private Category $category,
        private Price $price
    ) {
    }

    public static function create(
        string $sku,
        string $name,
        Category $category,
        int $price
    ) {
        if (empty($sku) || strlen($sku) > 36) {
            throw new InvalidProductSkuException();
        }

        if (empty($name) || strlen($name) > 128) {
            throw new InvalidProductNameException();
        }

        return new self(
            $sku,
            $name,
            $category,
            Price::create($price, Currency::create(Currency::EURO))
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

    public function getPrice(): Price
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
