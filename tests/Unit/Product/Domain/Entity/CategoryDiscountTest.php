<?php

namespace Tests\Unit\Product\Domain\Entity;

use App\Product\Domain\Entity\CategoryDiscount;
use App\Product\Domain\Exception\InvalidDiscountParametersException;
use App\Product\Domain\ValueObject\CategoryId;
use PHPUnit\Framework\TestCase;

class CategoryDiscountTest extends TestCase
{
    public function testCanCreateCategoryDiscount(): void
    {
        $categoryId = new CategoryId();
        $discount = 30;

        $sut = CategoryDiscount::create($categoryId, $discount);

        $this->assertSame($categoryId->id(), $sut->getCategoryId());
        $this->assertSame($discount, $sut->getDiscount());
    }

    public function testCanNotCreateCategoryDiscountByEmptyId(): void
    {
        $this->expectException(InvalidDiscountParametersException::class);

        $categoryId = '';
        $discount = 30;

        CategoryDiscount::create($categoryId, $discount);
    }

    public function testCanNotCreateCategoryDiscountByLowerDiscount(): void
    {
        $this->expectException(InvalidDiscountParametersException::class);

        $categoryId = new CategoryId();
        $discount = -1;

        CategoryDiscount::create($categoryId, $discount);
    }

    public function testCanNotCreateCategoryDiscountByHigherDiscount(): void
    {
        $this->expectException(InvalidDiscountParametersException::class);

        $categoryId = new CategoryId();
        $discount = 101;

        CategoryDiscount::create($categoryId, $discount);
    }
}
