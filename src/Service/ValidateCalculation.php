<?php 

declare(strict_types=1);

namespace App\Service;

use App\Service\BaseValidation;
use App\Exception\DateRangeException;
use InvalidArgumentException;

final readonly class ValidateCalculation extends BaseValidation
{
    private const MIN_DATE = '2023-01-01';
    private const MAX_DATE = '2023-12-31';

    public function validate(\stdClass $data): bool
    {
        if (!isset($data->currency) || !isset($data->date) || !isset($data->value)) {
            throw new InvalidArgumentException('Invalid arguments, required: currency, date, value');
        }

        if (strtotime($data->date) < strtotime(self::MIN_DATE) || strtotime($data->date) > strtotime(self::MAX_DATE)) {
            throw new DateRangeException();
        } else {
            if ($this->checkValue(+$data->value)) {
                return true;
            } else {
                throw new \InvalidArgumentException();
            }
        }
    }

    private function checkValue(float $value): bool
    {
        return $value < 0 ? false : true;
    }
}