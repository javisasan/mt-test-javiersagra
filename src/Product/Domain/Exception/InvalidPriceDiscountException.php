<?php

namespace App\Product\Domain\Exception;

use DomainException;

class InvalidPriceDiscountException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Invalid Price Discount value', 8007);
    }
}
