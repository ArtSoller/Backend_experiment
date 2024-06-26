<?php

namespace App\Infrastructure\Database\Entity;

use App\Infrastructure\Database\Repository\CurrenciesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurrenciesRepository::class)]
class Currencies
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $currency_id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $rate = null;

    #[ORM\Column]
    private ?int $expert_rating = null;

    public function getCurrencyId(): ?int
    {
        return $this->currency_id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(string $rate): static
    {
        $this->rate = $rate;

        return $this;
    }

    public function getExpert(): ?int
    {
        return $this->expert_rating;
    }

    public function setExpert(string $expert_rating): static
    {
        $this->expert_rating = $expert_rating;

        return $this;
    }
}