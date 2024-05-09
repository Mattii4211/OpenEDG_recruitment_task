<?php

namespace App\Repository;

use App\Entity\CurrencyExchangeRate;
use App\ValueObject\MinMaxRate;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CurrencyExchangeRate>
 */
class CurrencyExchangeRateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CurrencyExchangeRate::class);
    }

    public function findMinMax(string $period, int $range, string $currency): ?MinMaxRate
    {
        $data = $this->createQueryBuilder('c')
            ->select('MIN(c.value) AS min, MAX(c.value) AS max')
            ->where("c.$period = :range")
            ->andWhere('c.name = :name')
            ->setParameter('range', $range)
            ->setParameter('name', $currency)
            ->getQuery()
            ->getOneOrNullResult();

        return $data ? new MinMaxRate($data['max'], $data['min']) : null;
    }

    public function getValueByDate(DateTime $date, string $currency): ?float
    {
        $data = $this->createQueryBuilder('c')
            ->select('c.value')
            ->where('c.fullDate = :fullDate')
            ->andWhere('c.name = :name')
            ->setParameter('name', $currency)
            ->setParameter('fullDate', $date)
            ->getQuery()
            ->getOneOrNullResult();

        return $data ? +$data['value'] : null;
    }
}
