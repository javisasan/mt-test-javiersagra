<?php

namespace App\Product\Domain\Service;

interface ProductServiceInterface
{
    public function getProductsWithDiscount(): array;
}
