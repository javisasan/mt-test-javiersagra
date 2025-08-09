<?php

namespace App\Product\Infrastructure\Persistence\Doctrine\Repository;

use App\Product\Domain\Entity\CategoryDiscount;
use App\Product\Domain\Repository\CategoryDiscountRepositoryInterface;
use App\SharedKernel\Application\Cache\Adapter\CacheAdapterInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineCategoryDiscountRepository extends ServiceEntityRepository  implements CategoryDiscountRepositoryInterface
{
    private const CACHE_CATEGORY_DISCOUNT_KEY = 'category_discount_';

    private EntityManagerInterface $entityManager;
    private CacheAdapterInterface $cache;

    public function __construct(
        ManagerRegistry $registry,
        CacheAdapterInterface $cache
    ) {
        parent::__construct($registry, CategoryDiscount::class);
        $this->entityManager = $this->getEntityManager();
        $this->cache = $cache;
    }

    public function findOneByCategory(string $categoryId): ?CategoryDiscount
    {
        $cacheKey = $this->getCacheKey($categoryId);
        $categoryDiscount = $this->cache->get($cacheKey);

        if ($categoryDiscount !== false) {
            return $categoryDiscount;
        }

        $categoryDiscount = $this->findOneBy(['categoryId' => $categoryId]);

        $this->cache->set($cacheKey, $categoryDiscount);

        return $categoryDiscount;
    }

    public function save(CategoryDiscount $categoryDiscount): void
    {
        $this->entityManager->persist($categoryDiscount);
        $this->entityManager->flush();
    }

    private function getCacheKey(string $key): string
    {
        return self::CACHE_CATEGORY_DISCOUNT_KEY . $key;
    }
}
