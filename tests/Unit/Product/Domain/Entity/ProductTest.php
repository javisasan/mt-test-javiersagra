<?php

namespace Tests\Unit\Product\Domain\Entity;

use App\Product\Domain\Entity\Category;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Exception\InvalidProductNameException;
use App\Product\Domain\Exception\InvalidProductSkuException;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testCanCreateProduct(): void
    {
        $sku = '000001';
        $productName = 'Some dummy product name';
        $categoryName = 'Some dummy category name';
        $category = Category::create($categoryName);
        $price = 15000;

        $sut = Product::create(
            $sku,
            $productName,
            $category,
            $price
        );

        $this->assertSame($sku, $sut->getSku());
        $this->assertSame($productName, $sut->getName());
        $this->assertSame($categoryName, $sut->getCategory()->getName());
        $this->assertSame($price, $sut->getPrice()->getValue());
    }

    public function testCanNotCreateProductBecauseTooLongSku(): void
    {
        $this->expectException(InvalidProductSkuException::class);

        $sku = '00000112341234123412341234123412341234123412341234';
        $productName = 'Some dummy product name';
        $categoryName = 'Some dummy category name';
        $category = Category::create($categoryName);
        $price = 15000;

        Product::create(
            $sku,
            $productName,
            $category,
            $price
        );
    }

    public function testCanNotCreateProductBecauseEmptySku(): void
    {
        $this->expectException(InvalidProductSkuException::class);

        $sku = '';
        $productName = 'Some dummy product name';
        $categoryName = 'Some dummy category name';
        $category = Category::create($categoryName);
        $price = 15000;

        Product::create(
            $sku,
            $productName,
            $category,
            $price
        );
    }

    public function testCanNotCreateProductBecauseTooLongName(): void
    {
        $this->expectException(InvalidProductNameException::class);

        $sku = '000001';
        $productName = 'Some dummy product name Some dummy product name Some dummy product name Some dummy product name Some dummy product name Some dummy product name Some dummy product name Some dummy product name Some dummy product name ';
        $categoryName = 'Some dummy category name';
        $category = Category::create($categoryName);
        $price = 15000;

        Product::create(
            $sku,
            $productName,
            $category,
            $price
        );
    }

    public function testCanNotCreateProductBecauseEmptyName(): void
    {
        $this->expectException(InvalidProductNameException::class);

        $sku = '000001';
        $productName = '';
        $categoryName = 'Some dummy category name';
        $category = Category::create($categoryName);
        $price = 15000;

        Product::create(
            $sku,
            $productName,
            $category,
            $price
        );
    }
}
