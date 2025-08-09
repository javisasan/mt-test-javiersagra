<?php

namespace App\Product\Domain\Service;

use App\Product\Domain\Entity\Product;

interface ProductServiceInterface
{
    public function decideWhichDiscount(array $discounts): int;
    public function calculatePriceWithDiscount(Product $product, int $discountPercent): int;
}
