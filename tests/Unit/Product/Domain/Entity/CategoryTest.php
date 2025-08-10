<?php

namespace Tests\Unit\Product\Domain\Entity;

use App\Product\Domain\Entity\Category;
use App\Product\Domain\Exception\InvalidCategoryNameException;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    public function testCanCreateCategory(): void
    {
        $name = 'Some dummy category name';

        $sut = Category::create($name);

        $this->assertSame($name, $sut->getName());
    }

    public function testCanNotCreateCategoryWithEmptyName(): void
    {
        $this->expectException(InvalidCategoryNameException::class);

        $name = '';

        Category::create($name);
    }
}
