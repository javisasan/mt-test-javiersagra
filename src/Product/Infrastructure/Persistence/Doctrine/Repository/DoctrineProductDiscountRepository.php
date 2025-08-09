<?php

namespace App\Product\Infrastructure\Persistence\Doctrine\Repository;

use App\Product\Domain\Entity\ProductDiscount;
use App\Product\Domain\Repository\ProductDiscountRepositoryInterface;
use App\SharedKernel\Application\Cache\Adapter\CacheAdapterInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineProductDiscountRepository extends ServiceEntityRepository  implements ProductDiscountRepositoryInterface
{
    private const CACHE_PRODUCT_DISCOUNT_KEY = 'product_discount_';

    private EntityManagerInterface $entityManager;
    private CacheAdapterInterface $cache;

    public function __construct(
        ManagerRegistry $registry,
        CacheAdapterInterface $cache
    ) {
        parent::__construct($registry, ProductDiscount::class);
        $this->entityManager = $this->getEntityManager();
        $this->cache = $cache;
    }

    public function findOneBySku(string $sku): ?ProductDiscount
    {
        $cacheKey = $this->getCacheKey($sku);
        $productDiscount = $this->cache->get($cacheKey);

        if ($productDiscount !== false) {
            return $productDiscount;
        }

        $productDiscount = $this->findOneBy(['productSku' => $sku]);

        $this->cache->set($cacheKey, $productDiscount);

        return $productDiscount;
    }

    public function save(ProductDiscount $productDiscount): void
    {
        $this->entityManager->persist($productDiscount);
        $this->entityManager->flush();
    }

    private function getCacheKey(string $key): string
    {
        return self::CACHE_PRODUCT_DISCOUNT_KEY . $key;
    }
}
