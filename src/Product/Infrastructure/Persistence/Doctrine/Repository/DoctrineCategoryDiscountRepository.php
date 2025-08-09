<?php

namespace App\Product\Infrastructure\Persistence\Doctrine\Repository;

use App\Product\Domain\Entity\CategoryDiscount;
use App\Product\Domain\Repository\CategoryDiscountRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineCategoryDiscountRepository extends ServiceEntityRepository  implements CategoryDiscountRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryDiscount::class);
        $this->entityManager = $this->getEntityManager();
    }

    public function findOneByCategory(string $categoryId): ?CategoryDiscount
    {
        return $this->findOneBy(['categoryId' => $categoryId]);
    }

    public function save(CategoryDiscount $categoryDiscount): void
    {
        $this->entityManager->persist($categoryDiscount);
        $this->entityManager->flush();
    }
}
