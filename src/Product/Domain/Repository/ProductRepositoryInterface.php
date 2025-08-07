<?php

namespace App\Product\Domain\Repository;

use App\Product\Domain\Entity\Product;

interface ProductRepositoryInterface
{
    public function findAllProducts(): array;
    public function findProductsByCategory(string $category): array;
    public function findOneById(string $productId): ?Product;
    public function save(Product $product): void;
}
