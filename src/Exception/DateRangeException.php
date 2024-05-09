<?php

declare(strict_types=1);

namespace App\Exception;
use Exception;

final class DateRangeException extends Exception
{
    public function __construct() 
    {
        parent::__construct('Incorrect date range');
    }
}