<?php

namespace App\Product\Infrastructure\UserInterface\Api\Controller;

use App\Product\Application\Query\ProductsQuery;
use App\SharedKernel\Infrastructure\Messenger\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{
    public function __construct(
        private QueryBus $messageBus
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $categoryFilter = $request->query->get('category');
        $priceLessThanFilter = $request->query->get('priceLessThan');

        $response = $this->messageBus->dispatch(
            new ProductsQuery(
                $categoryFilter,
                $priceLessThanFilter
            )
        );

        dd($response);

        return new JsonResponse(
            //$this->transformer->transform($response)
        );
    }
}
