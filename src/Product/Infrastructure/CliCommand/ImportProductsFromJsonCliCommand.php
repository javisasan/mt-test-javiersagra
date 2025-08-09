<?php

namespace App\Product\Infrastructure\CliCommand;

use App\Product\Domain\Entity\Category;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Repository\CategoryRepositoryInterface;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'product:import-from-json')]
class ImportProductsFromJsonCliCommand
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private CategoryRepositoryInterface $categoryRepository
    )
    {
    }

    public function __invoke(OutputInterface $output)
    {
        $output->writeln('== Importing Products from JSON file...');
        $products = $this->readProductsFromJsonFile();

        foreach ($products as $product) {
            $category = $this->categoryRepository->findOneByName($product['category']);
            if (empty($category)) {
                $category = Category::create($product['category']);
            }

            $productEntity = $this->productRepository->findOneBySku($product['sku']);
            if (!empty($productEntity)) {
                continue;
            }

            $productEntity = Product::create(
                $product['sku'],
                $product['name'],
                $category,
                $product['price'],
            );
            $this->productRepository->save($productEntity);
            $output->write('.');
        }

        $output->writeln("\n== Product import finished!");

        return Command::SUCCESS;
    }

    public function readProductsFromJsonFile(): array
    {
        $file = __DIR__ . '/../../../../fixtures/products.json';

        $fileContent = file_get_contents($file);

        $data = json_decode($fileContent, true, 512, JSON_THROW_ON_ERROR);

        if (!isset($data['products'])) {
            throw new Exception('There are no Products to import in JSON file!');
        }

        return $data['products'];
    }
}
