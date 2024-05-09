<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\IncorrectCurrencyException;
use App\Exception\IncorrectPeriodException;
use App\Service\BaseValidation;
use InvalidArgumentException;

final readonly class ValidateMinMaxRequest extends BaseValidation
{
    public function validate(\stdClass $data): bool
    {
        if (!isset($data->currency) || !isset($data->period) || !isset($data->range)) {
            throw new InvalidArgumentException('Invalid arguments, required: currency, period, range');
        }

        if ($this->checkCorrectCurrency($data->currency)) {
            if ($this->checkCorrectPeriodRange($data->period, (int)$data->range)) {
                return true;
            } else {
                throw new IncorrectPeriodException();
            }
        } else {
            throw new IncorrectCurrencyException();
        }
    }

    private function checkCorrectPeriodRange(string $period, int $range): bool
    {
        switch ($period) {
            case 'week':
                return $range > 0 && $range <= 52;
            case 'month':
                return $range > 0 && $range <= 12;
            case 'quarter':
                return $range > 0 && $range <= 4;
        }
        return false;
    }
}