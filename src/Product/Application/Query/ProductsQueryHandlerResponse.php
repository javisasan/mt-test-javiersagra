<?php

namespace App\Product\Application\Query;

use App\Product\Application\Dto\ProductDto;

class ProductsQueryHandlerResponse
{
    private array $products = [];

    public function appendProduct(ProductDto $product): void
    {
        $this->products[] = $product;
    }

    public function getProducts(): array
    {
        return $this->products;
    }
}
