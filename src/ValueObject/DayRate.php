<?php

declare(strict_types=1);

namespace App\ValueObject;

use App\Enum\Currency;
use DateTime;

final readonly class DayRate 
{
    public function __construct(
        private Currency $currency, 
        private float $value, 
        private DateTime $fullDate
    ) {}

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getFullDate(): DateTime
    {
        return $this->fullDate;
    }
}