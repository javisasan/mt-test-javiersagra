<?php

namespace App\Product\Infrastructure\Persistence\Doctrine\Repository;

use App\Product\Domain\Entity\Product;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Order;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineProductRepository extends ServiceEntityRepository  implements ProductRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
        $this->entityManager = $this->getEntityManager();
    }

    public function findOneBySku(string $sku): ?Product
    {
        return $this->findOneBy(['sku' => $sku]);
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
}
