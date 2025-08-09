<?php

namespace App\Product\Application\Transformer;

use App\Product\Application\Query\ProductsQueryHandlerResponse;

interface ProductsTransformerInterface
{
    public function transform(ProductsQueryHandlerResponse $response): array;
}
