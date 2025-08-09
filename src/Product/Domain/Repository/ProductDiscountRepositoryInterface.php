<?php

namespace App\Product\Domain\Repository;

use App\Product\Domain\Entity\ProductDiscount;

interface ProductDiscountRepositoryInterface
{
    public function findOneBySku(string $sku): ?ProductDiscount;
    public function save(ProductDiscount $productDiscount): void;
}
