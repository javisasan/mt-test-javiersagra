<?php

namespace Tests\E2E\Product\Infrastructure\UserInterface\Api\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProductsControllerTest extends WebTestCase
{
    public function testProductsSuccessful()
    {
        $client = static::createClient();

        $client->request('GET', '/products');

        /** @var Response $response */
        $response = $client->getResponse();

        $this->assertSame(200, $response->getStatusCode());
    }
}
