<?php

namespace App\Product\Infrastructure\Persistence\Doctrine\Repository;

use App\Product\Domain\Entity\ProductDiscount;
use App\Product\Domain\Repository\ProductDiscountRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineProductDiscountRepository extends ServiceEntityRepository  implements ProductDiscountRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductDiscount::class);
        $this->entityManager = $this->getEntityManager();
    }

    public function findOneBySku(string $sku): ?ProductDiscount
    {
        return $this->findOneBy(['productSku' => $sku]);
    }

    public function save(ProductDiscount $productDiscount): void
    {
        $this->entityManager->persist($productDiscount);
        $this->entityManager->flush();
    }
}
