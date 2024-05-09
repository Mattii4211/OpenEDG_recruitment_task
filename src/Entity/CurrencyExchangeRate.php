<?php

namespace App\Entity;

use App\Repository\CurrencyExchangeRateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurrencyExchangeRateRepository::class)]
#[ORM\Index(columns: ['name'], name: 'name_idx')]
#[ORM\Index(columns: ['week'], name: 'week_idx')]
#[ORM\Index(columns: ['month'], name: 'month_idx')]
#[ORM\Index(columns: ['quarter'], name: 'quarter_idx')]

class CurrencyExchangeRate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 3, nullable: false)]
    private string $name;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private \DateTimeInterface $fullDate;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 3)]
    private string $value;

    #[ORM\Column(type: Types::SMALLINT)]
    private int $week;

    #[ORM\Column(type: Types::SMALLINT)]
    private int $month;

    #[ORM\Column(type: Types::SMALLINT)]
    private int $quarter;

    #[ORM\Column(type: Types::SMALLINT)]
    private int $year;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getFullDate(): \DateTimeInterface
    {
        return $this->fullDate;
    }

    public function setFullDate(\DateTimeInterface $fullDate): static
    {
        $this->fullDate = $fullDate;

        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getWeek(): int
    {
        return $this->week;
    }

    public function setWeek(int $week): static
    {
        $this->week = $week;

        return $this;
    }

    public function getMonth(): int
    {
        return $this->month;
    }

    public function setMonth(int $month): static
    {
        $this->month = $month;

        return $this;
    }

    public function getQuarter(): int
    {
        return $this->quarter;
    }

    public function setQuarter(int $quarter): static
    {
        $this->quarter = $quarter;

        return $this;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;

        return $this;
    }
}
