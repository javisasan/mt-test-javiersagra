<?php

namespace App\Product\Domain\Exception;

use DomainException;

class InvalidPriceException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Invalid Price value', 8006);
    }
}
