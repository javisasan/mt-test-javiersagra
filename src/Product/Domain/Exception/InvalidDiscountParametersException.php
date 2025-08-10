<?php

namespace App\Product\Domain\Exception;

use DomainException;

class InvalidDiscountParametersException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Could not create discount', 8002);
    }
}
