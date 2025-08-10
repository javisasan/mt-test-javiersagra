<?php

namespace App\Product\Domain\Repository;

use App\Product\Domain\Entity\Product;

interface ProductRepositoryInterface
{
    public function findProductsByCategoryAndPriceLessThan(
        string $category = null,
        ?int $priceLessThan = null,
        ?int $offset = 0,
        ?int $limit = 5
    ): array;
    public function findOneBySku(string $sku): ?Product;
    public function save(Product $product): void;
}
