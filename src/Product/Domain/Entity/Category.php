<?php

namespace App\Product\Domain\Entity;

use App\Product\Domain\Exception\InvalidCategoryNameException;
use App\Product\Domain\ValueObject\CategoryId;

class Category
{
    private string $id;
    private string $name;

    private function __construct(
        CategoryId $id,
        string $name
    ) {
        $this->id = $id;
        $this->name = $name;
    }

    public static function create(
        string $name,
    ) {
        if (empty($name)) {
            throw new InvalidCategoryNameException();
        }

        return new self(
            new CategoryId(),
            $name
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
