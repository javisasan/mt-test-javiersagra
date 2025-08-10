<?php

namespace App\Tests\Integration;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class IntegrationTestCase extends KernelTestCase
{
    /** @var ContainerInterface */
    protected $testContainer;

    /** @var EntityManager */
    protected $em;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();
        $this->testContainer = $kernel->getContainer();
        $this->em = $this->testContainer
            ->get('doctrine')
            ->getManager();
    }
}
