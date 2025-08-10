<?php

namespace App\Product\Domain\Service;

use App\Product\Domain\Entity\AbstractDiscount;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Exception\InvalidPriceDiscountException;

class ProductService implements ProductServiceInterface
{

    public function decideWhichDiscount(array $discounts): int
    {
        $discountPercent = 0;

        /** @var AbstractDiscount $discount */
        foreach ($discounts as $discount) {
            if ($discount->getDiscount() > $discountPercent) {
                $discountPercent = $discount->getDiscount();
            }
        }

        return $discountPercent;
    }

    public function calculatePriceWithDiscount(Product $product, int $discountPercent): int
    {
        if ($discountPercent === 0) {
            return $product->getPrice()->getValue();
        }

        if ($discountPercent < 0 || $discountPercent > 100) {
            throw new InvalidPriceDiscountException();
        }

        $price = $product->getPrice()->getValue();

        $discount = $price * ($discountPercent / 100);

        return $price - $discount;
    }
}
