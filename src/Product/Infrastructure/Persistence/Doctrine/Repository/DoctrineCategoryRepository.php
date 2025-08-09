<?php

namespace App\Product\Infrastructure\Persistence\Doctrine\Repository;

use App\Product\Domain\Entity\Category;
use App\Product\Domain\Repository\CategoryRepositoryInterface;
use App\SharedKernel\Application\Cache\Adapter\CacheAdapterInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineCategoryRepository extends ServiceEntityRepository  implements CategoryRepositoryInterface
{
    private const CACHE_CATEGORY_KEY = 'category_';

    private EntityManagerInterface $entityManager;
    private CacheAdapterInterface $cache;

    public function __construct(
        ManagerRegistry $registry,
        CacheAdapterInterface $cache
    ) {
        parent::__construct($registry, Category::class);
        $this->entityManager = $this->getEntityManager();
        $this->cache = $cache;
    }

    public function findOneById(string $id): ?Category
    {
        $cacheKey = $this->getCacheKey($id);
        $category = $this->cache->get($cacheKey);

        if ($category !== false) {
            return $category;
        }

        $category = $this->findOneBy(['id' => $id]);

        $this->cache->set($cacheKey, $category);

        return $category;
    }

    public function findOneByName(string $name): ?Category
    {
        return $this->findOneBy(['name' => $name]);
    }

    public function save(Category $category): void
    {
        $this->entityManager->persist($category);
        $this->entityManager->flush();
    }

    private function getCacheKey(string $key): string
    {
        return self::CACHE_CATEGORY_KEY . $key;
    }
}
