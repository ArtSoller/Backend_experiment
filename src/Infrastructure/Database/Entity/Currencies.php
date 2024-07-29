<?php

namespace App\Infrastructure\Database\Entity;

use App\Infrastructure\Database\Repository\CurrenciesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\Column(type: 'json')]
    private array $rates = [];

    #[ORM\Column]
    private ?int $expert_rating = null;

    #[ORM\OneToMany(targetEntity: Rules::class, mappedBy: 'currency')]
    private Collection $rules;

    public function __construct()
    {
        $this->rules = new ArrayCollection();
    }

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
        return end($this->rates) ?: null;
    }

    public function setRate(float $rate): static
    {
        $this->rates[] = $rate;
        if (count($this->rates) > 5) {
            array_shift($this->rates);
        }
        return $this;
    }

    public function getExpert(): ?int
    {
        return $this->expert_rating;
    }

    public function setExpert(int $expert_rating): static
    {
        $this->expert_rating = $expert_rating;
        return $this;
    }

    public function getRules(): Collection
    {
        return $this->rules;
    }

    public function setRules(Collection $rules): static
    {
        $this->rules = $rules;
        return $this;
    }

    public function getRates(): array
    {
        return $this->rates;
    }

    public function setRates(array $rates): static
    {
        $this->rates = array_slice($rates, -5);
        return $this;
    }
}