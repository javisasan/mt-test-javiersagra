<?php

namespace App\Product\Domain\Service;

use App\Product\Domain\Entity\AbstractDiscount;
use App\Product\Domain\Entity\Product;

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


        /*
        return [
            'sku' => $product->getSku(),
            'name' => $product->getName(),
            'category' => $product->getCategory()->getName(),
            'price' => [
                'original' => $product->getPrice(),
                'final' => $this->calculatePriceWithDiscount($product->getPrice(), $discountPercent),
                'discount_percentage' => $discountPercent . '%',
                'currency' => 'EUR',
            ]
        ];
         */
    }

    public function calculatePriceWithDiscount(Product $product, int $discountPercent): int
    {
        if ($discountPercent === 0) {
            return $product->getPrice();
        }

        $price = $product->getPrice();

        $discount = $price * ($discountPercent / 100);

        return $price - $discount;
    }
}
