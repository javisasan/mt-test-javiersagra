<?php

namespace Tests\Unit\Product\Domain\Service;

use App\Product\Domain\Entity\Category;
use App\Product\Domain\Entity\CategoryDiscount;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Entity\ProductDiscount;
use App\Product\Domain\Exception\InvalidPriceDiscountException;
use App\Product\Domain\Service\ProductService;
use PHPUnit\Framework\TestCase;

class ProductServiceTest extends TestCase
{
    public function testCanRetrievePercentWithoutDiscounts(): void
    {
        $discounts = [];

        $sut = new ProductService();

        $discountPercent = $sut->decideWhichDiscount($discounts);

        $this->assertSame(0, $discountPercent);
    }

    public function testCanRetrieveHigherPercentFromCategory(): void
    {
        $discount1 = CategoryDiscount::create('dummy-category', 30);
        $discount2 = ProductDiscount::create('dummy-product', 15);
        $discounts = [
            $discount1,
            $discount2
        ];

        $sut = new ProductService();

        $discountPercent = $sut->decideWhichDiscount($discounts);

        $this->assertSame(30, $discountPercent);
    }

    public function testCanRetrieveHigherPercentFromProduct(): void
    {
        $discount1 = CategoryDiscount::create('dummy-category', 15);
        $discount2 = ProductDiscount::create('dummy-product', 30);
        $discounts = [
            $discount1,
            $discount2
        ];

        $sut = new ProductService();

        $discountPercent = $sut->decideWhichDiscount($discounts);

        $this->assertSame(30, $discountPercent);
    }

    public function testCanRetrievePriceWithDiscount(): void
    {
        $price = 71000;
        $discountPercent = 30;
        $expectedPrice = 49700;
        $category = Category::create('dummy category');

        $product = Product::create(
            '000001',
            'dummy product',
            $category,
            $price
        );

        $sut = new ProductService();

        $discountedPrice = $sut->calculatePriceWithDiscount($product, $discountPercent);

        $this->assertSame($expectedPrice, $discountedPrice);
    }

    public function testCanRetrievePriceWithZeroDiscount(): void
    {
        $price = 71000;
        $discountPercent = 0;
        $expectedPrice = 71000;
        $category = Category::create('dummy category');

        $product = Product::create(
            '000001',
            'dummy product',
            $category,
            $price
        );

        $sut = new ProductService();

        $discountedPrice = $sut->calculatePriceWithDiscount($product, $discountPercent);

        $this->assertSame($expectedPrice, $discountedPrice);
    }

    public function testCanNotRetrievePriceBecauseDiscountIsLowerThanZero(): void
    {
        $this->expectException(InvalidPriceDiscountException::class);
        $price = 71000;
        $discountPercent = -1;
        $category = Category::create('dummy category');

        $product = Product::create(
            '000001',
            'dummy product',
            $category,
            $price
        );

        $sut = new ProductService();

        $sut->calculatePriceWithDiscount($product, $discountPercent);
    }

    public function testCanNotRetrievePriceBecauseDiscountIsHigherThanHundred(): void
    {
        $this->expectException(InvalidPriceDiscountException::class);
        $price = 71000;
        $discountPercent = 101;
        $category = Category::create('dummy category');

        $product = Product::create(
            '000001',
            'dummy product',
            $category,
            $price
        );

        $sut = new ProductService();

        $sut->calculatePriceWithDiscount($product, $discountPercent);
    }
}
