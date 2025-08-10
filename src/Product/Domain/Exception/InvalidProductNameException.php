<?php

namespace App\Product\Domain\Exception;

use DomainException;

class InvalidProductNameException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Invalid product Name', 8005);
    }
}
