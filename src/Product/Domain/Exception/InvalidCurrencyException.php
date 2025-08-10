<?php

namespace App\Product\Domain\Exception;

use DomainException;

class InvalidCurrencyException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Currency code not valid', 8001);
    }
}
