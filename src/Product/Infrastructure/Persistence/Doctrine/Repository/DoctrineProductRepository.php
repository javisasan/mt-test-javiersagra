<?php
namespace App\Product\Infrastructure\Persistence\Doctrine\Repository;

use App\Product\Domain\Entity\Product;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineProductRepository extends ServiceEntityRepository  implements ProductRepositoryInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
        $this->entityManager = $this->getEntityManager();
    }

    public function findOneById(string $productId): ?Product
    {
        return $this->findOneBy(['id' => $productId]);
    }

    public function findAllProducts(): array
    {
        return $this->findAll();
    }

    public function findProductsByCategory(?string $category): array
    {
        return $this->findBy([
            'category' => $category
        ]);
    }

    public function save(Product $product): void
    {
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }
}
