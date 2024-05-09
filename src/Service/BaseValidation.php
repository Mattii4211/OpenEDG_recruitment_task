<?php

declare(strict_types=1);

namespace App\Service;

use App\Enum\Currency;

readonly class BaseValidation 
{
    protected function checkCorrectCurrency(string $currency): bool
    {
        $currencies = Currency::cases();
        return !!array_search(strtoupper($currency), array_column($currencies, 'name'));
    }
}