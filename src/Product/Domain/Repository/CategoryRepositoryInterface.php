<?php

namespace App\Product\Domain\Repository;

use App\Product\Domain\Entity\Category;

interface CategoryRepositoryInterface
{
    public function findOneById(string $id): ?Category;
    public function findOneByName(string $name): ?Category;
    public function save(Category $category): void;
}
