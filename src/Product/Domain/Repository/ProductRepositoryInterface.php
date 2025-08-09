<?php

namespace App\Product\Domain\Repository;

use App\Product\Domain\Entity\Product;

interface ProductRepositoryInterface
{
    public function findProductsByCategoryAndPriceLessThan(string $category = null, ?int $priceLessThan = null): array;
    public function findOneBySku(string $sku): ?Product;
    public function save(Product $product): void;
}
