<?php

declare(strict_types=1);

namespace App\Enum;

enum Currency 
{
    case PLN;
    case EUR;
    case USD;

    public function getApiName(): string
    {
        return match($this) {
            Currency::PLN => 'pln',
            Currency::USD => 'usd',
            Currency::EUR => 'eur'
        };
    }

    public function getName(): string
    {
        return match($this) {
            Currency::PLN => 'PLN',
            Currency::USD => 'USD',
            Currency::EUR => 'EUR'
        };
    }
}