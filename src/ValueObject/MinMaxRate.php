<?php

declare(strict_types=1);

namespace App\ValueObject;

final readonly class MinMaxRate
{
    public function __construct(
        private float $max,
        private float $min
    ) {}

    public function getMax(): float
    {
        return $this->max;
    }

    public function getMin(): float
    {
        return $this->min;
    }
}