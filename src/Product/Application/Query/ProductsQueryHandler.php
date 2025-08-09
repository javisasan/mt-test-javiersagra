<?php

namespace App\Product\Application\Query;

use App\Product\Application\Dto\ProductDto;
use App\Product\Application\Query\ProductsQuery;
use App\Product\Application\Query\ProductsQueryHandlerResponse;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Repository\CategoryDiscountRepositoryInterface;
use App\Product\Domain\Repository\CategoryRepositoryInterface;
use App\Product\Domain\Repository\ProductDiscountRepositoryInterface;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Product\Domain\Service\ProductServiceInterface;

class ProductsQueryHandler
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private CategoryRepositoryInterface $categoryRepository,
        private ProductDiscountRepositoryInterface $productDiscountRepository,
        private CategoryDiscountRepositoryInterface $categoryDiscountRepository,
        private ProductServiceInterface $service
    )
    {
    }

    public function __invoke(ProductsQuery $query): ProductsQueryHandlerResponse
    {
        $response = new ProductsQueryHandlerResponse();

        $products = $this->productRepository->findProductsByCategoryAndPriceLessThan(
            $query->getCategoryFilter(),
            $query->getPriceLessThanFilter()
        );

        /** @var Product $product */
        foreach ($products as $product) {
            $productDiscount = $this->productDiscountRepository->findOneBySku($product->getSku());
            $categoryDiscount = $this->categoryDiscountRepository->findOneByCategory($product->getCategory()->getId());

            $discountPercent = $this->service->decideWhichDiscount(
                array_filter([$productDiscount, $categoryDiscount])
            );

            $response->appendProduct(new ProductDto(
                $product->getSku(),
                $product->getName(),
                $product->getCategory()->getName(),
                $product->getPrice(),
                $this->service->calculatePriceWithDiscount($product, $discountPercent),
                $discountPercent,
                'EUR'
            ));
        }

        return $response;
    }
}
