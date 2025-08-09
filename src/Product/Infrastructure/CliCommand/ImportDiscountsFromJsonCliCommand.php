<?php

namespace App\Product\Infrastructure\CliCommand;

use App\Product\Domain\Entity\Category;
use App\Product\Domain\Entity\CategoryDiscount;
use App\Product\Domain\Entity\ProductDiscount;
use App\Product\Domain\Repository\CategoryDiscountRepositoryInterface;
use App\Product\Domain\Repository\CategoryRepositoryInterface;
use App\Product\Domain\Repository\ProductDiscountRepositoryInterface;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'discounts:import-from-json')]
class ImportDiscountsFromJsonCliCommand
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private CategoryRepositoryInterface $categoryRepository,
        private ProductDiscountRepositoryInterface $productDiscountRepository,
        private CategoryDiscountRepositoryInterface $categoryDiscountRepository
    )
    {
    }

    public function __invoke(OutputInterface $output)
    {
        $output->writeln('== Importing Discounts from JSON file...');
        $discounts = $this->readDiscountsFromJsonFile();

        foreach ($discounts['categories'] as $discountCategory) {
            $category = $this->categoryRepository->findOneByName($discountCategory['name']);
            if (empty($category)) {
                $category = Category::create($discountCategory['category']);
            }

            $discountCategoryEntity = $this->categoryDiscountRepository->findOneByCategory($category->getId());
            if (!empty($discountCategoryEntity)) {
                continue;
            }

            $discountCategoryEntity = CategoryDiscount::create(
                $category->getId(),
                $discountCategory['discount'],
            );
            $this->categoryDiscountRepository->save($discountCategoryEntity);
            $output->write('.');
        }

        foreach ($discounts['products'] as $discountProduct) {
            $product = $this->productRepository->findOneBySku($discountProduct['sku']);
            if (empty($product)) {
                continue;
            }

            $discountProductEntity = $this->productDiscountRepository->findOneBySku($product->getSku());
            if (!empty($discountProductEntity)) {
                continue;
            }

            $discountProductEntity = ProductDiscount::create(
                $product->getSku(),
                $discountProduct['discount'],
            );
            $this->productDiscountRepository->save($discountProductEntity);
            $output->write('.');
        }

        $output->writeln("\n== Discounts import finished!");

        return Command::SUCCESS;
    }

    public function readDiscountsFromJsonFile(): array
    {
        $file = __DIR__ . '/../../../../fixtures/discounts.json';

        $fileContent = file_get_contents($file);

        $data = json_decode($fileContent, true, 512, JSON_THROW_ON_ERROR);

        if (empty($data)) {
            throw new Exception('There are no Discounts to import in JSON file!');
        }

        return $data;
    }
}
