<?php

namespace App\Product\Infrastructure\Persistence\Doctrine\Repository;

use App\Product\Domain\Entity\Category;
use App\Product\Domain\Repository\CategoryRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineCategoryRepository extends ServiceEntityRepository  implements CategoryRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
        $this->entityManager = $this->getEntityManager();
    }

    public function findOneById(string $id): ?Category
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function findOneByName(string $name): ?Category
    {
        return $this->findOneBy(['name' => $name]);
    }

    public function findOneByNameasdf(string $name): ?Category
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('c.id, c.name')
            ->from(Category::class, 'c')
            ->where('c.name = :name')
            ->setParameter('name', 'boots');

        $query = $qb->getQuery();
        $result = $query->getResult();

        if (empty($result)) {
            return null;
        }
    }

    public function save(Category $category): void
    {
        $this->entityManager->persist($category);
        $this->entityManager->flush();
    }
}
