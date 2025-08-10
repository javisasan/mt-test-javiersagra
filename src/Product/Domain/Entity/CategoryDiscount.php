<?php

namespace App\Product\Domain\Entity;

use App\Product\Domain\Exception\InvalidDiscountParametersException;

class CategoryDiscount extends AbstractDiscount
{
    private function __construct(
        private string $categoryId,
        protected int $discount
    ) {
    }

    public static function create(
        string $categoryId,
        int $discount
    ) {
        if (empty($categoryId) || $discount < 0 || $discount > 100) {
            throw new InvalidDiscountParametersException();
        }

        return new self(
            $categoryId,
            $discount
        );
    }

    public function getCategoryId(): string
    {
        return $this->categoryId;
    }
}
