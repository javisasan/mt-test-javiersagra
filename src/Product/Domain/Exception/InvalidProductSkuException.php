<?php

namespace App\Product\Domain\Exception;

use DomainException;

class InvalidProductSkuException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Invalid product SKU', 8004);
    }
}
