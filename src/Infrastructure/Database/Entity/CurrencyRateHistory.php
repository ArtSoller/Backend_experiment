<?php

namespace App\Infrastructure\Database\Entity;

use App\Infrastructure\Database\Repository\CurrencyRateHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurrencyRateHistoryRepository::class)]
class CurrencyRateHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Currencies::class, inversedBy: 'rateHistory')]
    #[ORM\JoinColumn(name: 'currency_id', referencedColumnName: 'currency_id', onDelete: 'CASCADE')]
    private ?Currencies $currency = null;

    #[ORM\Column]
    private ?float $rate = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurrency(): ?Currencies
    {
        return $this->currency;
    }

    public function setCurrency(?Currencies $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(float $rate): static
    {
        $this->rate = $rate;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }
}
