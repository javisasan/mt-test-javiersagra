<?php

namespace App\Product\Application\Query;

use App\Product\Application\Query\ProductsQuery;
use App\Product\Application\Query\ProductsQueryHandlerResponse;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Product\Domain\Service\ProductServiceInterface;

class ProductsQueryHandler
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private ProductServiceInterface $productService
    )
    {
    }

    public function __invoke(ProductsQuery $query): ProductsQueryHandlerResponse
    {
        $products = $this->productRepository->findProductsByCategory($query->getCategoryFilter());
        dd($products);
        dd($query, 'asdf');
        return new ProductsQueryHandlerResponse();
    }
}
