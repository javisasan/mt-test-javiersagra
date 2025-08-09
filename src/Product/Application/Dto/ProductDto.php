<?php

namespace App\Product\Application\Dto;

class ProductDto
{
    public function __construct(
        private string $sku,
        private string $name,
        private string $category,
        private int $originalPrice,
        private int $finalPrice,
        private int $discountPercentage,
        private string $currency
    ) {
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getOriginalPrice(): int
    {
        return $this->originalPrice;
    }

    public function getFinalPrice(): int
    {
        return $this->finalPrice;
    }

    public function getDiscountPercentage(): int
    {
        return $this->discountPercentage;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
