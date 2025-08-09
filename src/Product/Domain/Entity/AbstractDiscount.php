<?php

namespace App\Product\Domain\Entity;

abstract class AbstractDiscount
{
    protected int $discount;

    public function __construct(int $discount)
    {
        $this->discount = $discount;
    }

    public function getDiscount(): int
    {
        return $this->discount;
    }
}
