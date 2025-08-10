<?php

namespace App\Product\Application\Query;

class ProductsQuery
{
    public function __construct(
        private ?string $categoryFilter,
        private ?string $priceLessThanFilter,
        private ?int $page
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

    public function getPage(): int
    {
        return empty($this->page) ? 1 : $this->page;
    }
}
