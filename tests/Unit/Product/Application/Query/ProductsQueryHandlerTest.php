<?php

namespace Tests\Unit\Product\Application\Query;

use App\Product\Application\Dto\ProductDto;
use App\Product\Application\Query\ProductsQuery;
use App\Product\Application\Query\ProductsQueryHandler;
use App\Product\Application\Query\ProductsQueryHandlerResponse;
use App\Product\Domain\Entity\Category;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Repository\CategoryDiscountRepositoryInterface;
use App\Product\Domain\Repository\CategoryRepositoryInterface;
use App\Product\Domain\Repository\ProductDiscountRepositoryInterface;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Product\Domain\Service\ProductServiceInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\MethodProphecy;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;

use function PHPUnit\Framework\any;

class ProductsQueryHandlerTest extends TestCase
{
    private Prophet $prophet;
    private ObjectProphecy|ProductRepositoryInterface $productRepository;
    private ObjectProphecy|CategoryRepositoryInterface $categoryRepository;
    private ObjectProphecy|ProductDiscountRepositoryInterface $productDiscountRepository;
    private ObjectProphecy|CategoryDiscountRepositoryInterface $categoryDiscountRepository;
    private ObjectProphecy|ProductServiceInterface $service;
    private ProductsQueryHandler $sut;

    protected function setUp(): void
    {
        $this->prophet = new Prophet();

        $this->productRepository = $this->prophet->prophesize(ProductRepositoryInterface::class);
        $this->categoryRepository = $this->prophet->prophesize(CategoryRepositoryInterface::class);
        $this->productDiscountRepository = $this->prophet->prophesize(ProductDiscountRepositoryInterface::class);
        $this->categoryDiscountRepository = $this->prophet->prophesize(CategoryDiscountRepositoryInterface::class);
        $this->service = $this->prophet->prophesize(ProductServiceInterface::class);

        $this->sut = new ProductsQueryHandler(
            $this->productRepository->reveal(),
            $this->categoryRepository->reveal(),
            $this->productDiscountRepository->reveal(),
            $this->categoryDiscountRepository->reveal(),
            $this->service->reveal()
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->prophet->checkPredictions();
    }

    public function testCanHandleQueryWithEmptyResult(): void
    {
        $categoryFilter = 'boots';
        $priceLessThanFilter = 90000;
        $page = 1;

        $query = new ProductsQuery(
            $categoryFilter,
            $priceLessThanFilter,
            $page
        );

        /** @var MethodProphecy $findProductsExpectation */
        $findProductsExpectation = $this->productRepository
            ->findProductsByCategoryAndPriceLessThan($categoryFilter, $priceLessThanFilter, $page, 5)
            ->willReturn([]);

        /** @var MethodProphecy $findProductDiscountExpectation */
        $findProductDiscountExpectation = $this->productDiscountRepository
            ->findOneBySku(Argument::any());

        /** @var MethodProphecy $findCategoryDiscountExpectation */
        $findCategoryDiscountExpectation = $this->categoryDiscountRepository
            ->findOneByCategory(Argument::any());

        /** @var MethodProphecy $findServiceExpectation */
        $findServiceExpectation = $this->service
            ->decideWhichDiscount(Argument::any());

        /** @var MethodProphecy $findServiceExpectation */
        $findServicePriceExpectation = $this->service
            ->calculatePriceWithDiscount(Argument:any());

        $result = ($this->sut)($query);

        $findProductsExpectation->shouldBeCalledOnce();
        $findProductDiscountExpectation->shouldNotBeCalled();
        $findCategoryDiscountExpectation->shouldNotBeCalled();
        $findServiceExpectation->shouldNotBeCalled();
        $findServicePriceExpectation->shouldNotBeCalled();

        $this->assertEquals((new ProductsQueryHandlerResponse()), $result);
    }

    public function testCanHandleQueryWithResults(): void
    {
        $categoryFilter = 'boots';
        $priceLessThanFilter = 90000;
        $page = 1;

        $query = new ProductsQuery(
            $categoryFilter,
            $priceLessThanFilter,
            $page
        );

        $sku = '000001';
        $productPrice = 89000;
        $categoryName = 'dummy category';
        $category = Category::create($categoryName);
        $retrievedProduct = Product::create(
            '000001',
            'dummy product',
            $category,
            $productPrice
        );

        /** @var MethodProphecy $findProductsExpectation */
        $findProductsExpectation = $this->productRepository
            ->findProductsByCategoryAndPriceLessThan($categoryFilter, $priceLessThanFilter, $page, 5)
            ->willReturn([$retrievedProduct]);

        /** @var MethodProphecy $findProductDiscountExpectation */
        $findProductDiscountExpectation = $this->productDiscountRepository
            ->findOneBySku($sku)
            ->willReturn(null);

        /** @var MethodProphecy $findCategoryDiscountExpectation */
        $findCategoryDiscountExpectation = $this->categoryDiscountRepository
            ->findOneByCategory($category->getId())
            ->willReturn(null);

        /** @var MethodProphecy $findServiceExpectation */
        $findServiceExpectation = $this->service
            ->decideWhichDiscount([])
            ->willReturn(0);

        /** @var MethodProphecy $findServicePriceExpectation */
        $findServicePriceExpectation = $this->service
            ->calculatePriceWithDiscount($retrievedProduct, 0)
            ->willReturn($productPrice);

        $result = ($this->sut)($query);

        $findProductsExpectation->shouldBeCalledOnce();
        $findProductDiscountExpectation->shouldBeCalledOnce();
        $findCategoryDiscountExpectation->shouldBeCalledOnce();
        $findServiceExpectation->shouldBeCalledOnce();
        $findServicePriceExpectation->shouldBeCalledOnce();

        $this->assertSame(ProductDto::class, get_class($result->getProducts()[0]));
    }
}
