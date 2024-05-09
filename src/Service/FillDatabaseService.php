<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\CurrencyExchangeRate;
use Doctrine\ORM\EntityManagerInterface;

final class FillDatabaseService 
{
    private const INSERT_PACKAGE_LIMIT = 1000;
    public function __construct(private EntityManagerInterface $entityManagerInterface) {}

    public function insert(array $data): void
    {
        $counter = 0;
        foreach ($data as $dayRate) {
            $counter++;
            $fullDate = $dayRate->getFullDate();
            $currencyExchangeRate = new CurrencyExchangeRate();
            $currencyExchangeRate->setFullDate($fullDate);
            $currencyExchangeRate->setName($dayRate->getCurrency()->getName());
            $currencyExchangeRate->setWeek((int)$fullDate->format('W'));
            $currencyExchangeRate->setMonth((int)$fullDate->format('m'));
            $currencyExchangeRate->setQuarter($this->clacQuarter((int)$fullDate->format('m')));
            $currencyExchangeRate->setYear((int)$fullDate->format('Y'));
            $currencyExchangeRate->setValue(strval($dayRate->getValue()));
            $this->entityManagerInterface->persist($currencyExchangeRate);

            if ($counter > self::INSERT_PACKAGE_LIMIT) {
                // or without doctrine (pure SQL)
                $this->entityManagerInterface->flush();
                $counter = 0;
            }
            $this->entityManagerInterface->flush();
        }
    }

    private function clacQuarter(int $monthNumber): int
    {
        return (int)floor($monthNumber / 4) + 1;
    }
}