<?php

namespace App\Product\Domain\Repository;

use App\Product\Domain\Entity\CategoryDiscount;

interface CategoryDiscountRepositoryInterface
{
    public function findOneByCategory(string $categoryId): ?CategoryDiscount;
    public function save(CategoryDiscount $product): void;
}
