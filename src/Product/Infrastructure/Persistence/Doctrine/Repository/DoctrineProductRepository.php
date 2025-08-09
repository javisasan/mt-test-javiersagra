<?php

namespace App\Product\Infrastructure\Persistence\Doctrine\Repository;

use App\Product\Domain\Entity\Product;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\SharedKernel\Application\Cache\Adapter\CacheAdapterInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Order;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineProductRepository extends ServiceEntityRepository  implements ProductRepositoryInterface
{
    private const CACHE_PRODUCT_KEY = 'product_';

    private EntityManagerInterface $entityManager;
    private CacheAdapterInterface $cache;

    public function __construct(
        ManagerRegistry $registry,
        CacheAdapterInterface $cache
    ) {
        parent::__construct($registry, Product::class);
        $this->entityManager = $this->getEntityManager();
        $this->cache = $cache;
    }

    public function findOneBySku(string $sku): ?Product
    {
        $cacheKey = $this->getCacheKey($sku);
        $product = $this->cache->get($cacheKey);

        if ($product !== false) {
            return $product;
        }

        $product = $this->findOneBy(['sku' => $sku]);

        $this->cache->set($cacheKey, $product);

        return $product;
    }

    public function findProductsByCategoryAndPriceLessThan(string $category = null, ?int $priceLessThan = null): array
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('p')
            ->from(Product::class, 'p')
            ->innerJoin('p.category', 'c')
            ->addOrderBy('p.price', Order::Ascending->value);

        if ($category) {
            $qb->where('c.name = :category')
                ->setParameter('category', $category);
        }

        if ($priceLessThan) {
            $qb->andWhere('p.price <= :priceLessThan')
                ->setParameter('priceLessThan', $priceLessThan);
        }

        $query = $qb->getQuery();

        return $query->getResult();
    }

    public function save(Product $product): void
    {
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }

    private function getCacheKey(string $key): string
    {
        return self::CACHE_PRODUCT_KEY . $key;
    }
}
