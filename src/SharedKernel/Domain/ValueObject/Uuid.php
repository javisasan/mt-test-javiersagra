<?php

namespace App\SharedKernel\Domain\ValueObject;

use Ramsey\Uuid\Uuid as RamseyUuid;

class Uuid
{
    public function __construct(protected ?string $id = null)
    {
        $this->id = $id ?: RamseyUuid::uuid4()->toString();
    }

    public function id(): string
    {
        return $this->id;
    }

    public function equals(Uuid $uuid): bool
    {
        return $this->id() === $uuid->id();
    }

    public function __toString(): string
    {
        return $this->id();
    }
}
