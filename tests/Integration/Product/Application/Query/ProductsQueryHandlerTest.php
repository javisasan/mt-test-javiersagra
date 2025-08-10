<?php

namespace Tests\Integration\Product\Application\Query;

use App\Product\Application\Dto\ProductDto;
use App\Product\Application\Query\ProductsQuery;
use App\Product\Application\Query\ProductsQueryHandler;
use App\Tests\Integration\IntegrationTestCase;

class ProductsQueryHandlerTest extends IntegrationTestCase
{
    private string $categoryId;
    private string $categoryName;
    private string $productSku;
    private string $productName;
    private string $productPrice;
    private string $productPriceDiscounted;
    private string $discount;
    private string $currency;

    protected function setUp(): void
    {
        parent::setUp();

        $this->categoryId = 'dummy-category-id';
        $this->categoryName = 'dummy category name';
        $this->productSku = 'prod-id';
        $this->productName = 'dummy product name';
        $this->productPrice = 71000;
        $this->productPriceDiscounted = 49700;
        $this->discount = 30;
        $this->currency = 'EUR';

        $this->tearDown();
        $this->tearUp();
    }

    public function testCanGetProductsQueryHandlerResponse(): void
    {
        $query = new ProductsQuery($this->categoryName, null, null);

        $sut = $this->testContainer->get(ProductsQueryHandler::class);

        $result = ($sut)($query);

        /** @var ProductDto $resultDto */
        $resultDto = $result->getProducts()[0];

        $this->assertEquals(ProductDto::class, get_class($resultDto));
        $this->assertSame($this->productSku, $resultDto->getSku());
        $this->assertSame($this->productName, $resultDto->getName());
        $this->assertSame($this->categoryName, $resultDto->getCategory());
        $this->assertEquals($this->productPrice, $resultDto->getOriginalPrice());
        $this->assertEquals($this->productPriceDiscounted, $resultDto->getFinalPrice());
        $this->assertEquals($this->discount, $resultDto->getDiscountPercentage());
        $this->assertSame($this->currency, $resultDto->getCurrency());
    }

    protected function tearUp(): void
    {
        $conn = $this->em->getConnection();

        $conn->prepare('SET FOREIGN_KEY_CHECKS=0;')->executeQuery();

        // Create Category
        $sql = <<< SQL
INSERT INTO `categories` (`id`, `name`)
VALUES
(:id, :name);
SQL;
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('id', $this->categoryId);
        $stmt->bindValue('name', $this->categoryName);
        $stmt->executeQuery();

        // Create Product
        $sql = <<< SQL
INSERT INTO `products` (`sku`, `category`, `name`, `price`, `currency`)
VALUES
(:sku, :category, :name, :price, :currency);
SQL;
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('sku', $this->productSku);
        $stmt->bindValue('category', $this->categoryId);
        $stmt->bindValue('name', $this->productName);
        $stmt->bindValue('price', $this->productPrice);
        $stmt->bindValue('currency', $this->currency);
        $stmt->executeQuery();

        // Create Product Discount
        $sql = <<< SQL
INSERT INTO `product_discount` (`productSku`, `discount`)
VALUES
(:sku, :discount);
SQL;
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('sku', $this->productSku);
        $stmt->bindValue('discount', $this->discount);
        $stmt->executeQuery();

        $conn->prepare('SET FOREIGN_KEY_CHECKS=1;')->executeQuery();
    }

    protected function tearDown(): void
    {
        $conn = $this->em->getConnection();

        $stmt = $conn->prepare('DELETE FROM products WHERE sku = :sku;');
        $stmt->bindValue('sku', $this->productSku);
        $stmt->executeQuery();

        $stmt = $conn->prepare('DELETE FROM categories WHERE id = :id;');
        $stmt->bindValue('id', $this->categoryId);
        $stmt->executeQuery();

        $stmt = $conn->prepare('DELETE FROM product_discount WHERE productSku = :sku;');
        $stmt->bindValue('sku', $this->productSku);
        $stmt->executeQuery();
    }
}
