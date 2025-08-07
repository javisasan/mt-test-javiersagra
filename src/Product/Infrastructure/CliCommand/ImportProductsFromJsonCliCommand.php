<?php

namespace App\Product\Infrastructure\CliCommand;

use App\Product\Domain\Entity\Product;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'product:import-from-json')]
class ImportProductsFromJsonCliCommand
{
    public function __construct(
        private ProductRepositoryInterface $repository,
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function __invoke(OutputInterface $output)
    {
        $output->writeln('== Importing Products from JSON file...');
        $products = $this->readProductsFromJsonFile();

        foreach ($products as $product) {
            $productEntity = Product::create(
                $product['sku'],
                $product['name'],
                $product['category'],
                $product['price'],
            );
            $this->entityManager->persist($productEntity);
            $output->write('.');
        }

        $this->entityManager->flush();
        $output->writeln("\n== Product import finished!");

        return Command::SUCCESS;
    }

    public function readProductsFromJsonFile(): array
    {
        $file = __DIR__ . '/../../../../ops/mysql/products.json';

        $fileContent = file_get_contents($file);

        $data = json_decode($fileContent, true, 512, JSON_THROW_ON_ERROR);

        if (!isset($data['products'])) {
            throw new Exception('There are no Products to import in JSON file!');
        }

        return $data['products'];
    }
}
