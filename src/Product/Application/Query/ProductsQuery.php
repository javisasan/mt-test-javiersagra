<?php

namespace App\Product\Application\Query;

class ProductsQuery
{
    public function __construct(
        private ?string $categoryFilter,
        private ?string $priceLessThanFilter
    ) {
    }

    public function getCategoryFilter(): ?string
    {
        return $this->categoryFilter;
    }

    public function getPriceLessThanFilter(): ?string
    {
        return $this->priceLessThanFilter;
    }
}
