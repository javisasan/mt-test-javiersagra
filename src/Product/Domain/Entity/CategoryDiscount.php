<?php

namespace App\Product\Domain\Entity;

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
