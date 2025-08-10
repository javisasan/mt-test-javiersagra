<?php

namespace Tests\Unit\Product\Application\Query;

use App\Product\Application\Query\ProductsQuery;
use PHPUnit\Framework\TestCase;

class ProductsQueryTest extends TestCase
{
    public function testCanCreateProductsQuery(): void
    {
        $categoryFilter = 'boots';
        $priceLessThanFilter = 89000;
        $page = 1;

        $sut = new ProductsQuery(
            $categoryFilter,
            $priceLessThanFilter,
            $page,
        );

        $this->assertSame($categoryFilter, $sut->getCategoryFilter());
        $this->assertSame($priceLessThanFilter, $sut->getPriceLessThanFilter());
        $this->assertSame($page, $sut->getPage());
    }

    public function testCanCreateProductsQueryWithDefaultPage(): void
    {
        $categoryFilter = 'boots';
        $priceLessThanFilter = 89000;
        $page = null;

        $sut = new ProductsQuery(
            $categoryFilter,
            $priceLessThanFilter,
            $page,
        );

        $this->assertSame($categoryFilter, $sut->getCategoryFilter());
        $this->assertSame($priceLessThanFilter, $sut->getPriceLessThanFilter());
        $this->assertSame(1, $sut->getPage());
    }
}
