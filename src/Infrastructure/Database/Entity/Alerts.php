<?php

namespace App\Infrastructure\Database\Entity;

use App\Infrastructure\Database\Repository\AlertsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlertsRepository::class)]
class Alerts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Users::class, inversedBy: 'Alerts')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'user_id')]
    private ?Users $user;

    #[ORM\ManyToOne(targetEntity: Currencies::class, inversedBy: 'Alerts')]
    #[ORM\JoinColumn(name: 'currency_id', referencedColumnName: 'currency_id')]
    private ?Currencies $currencies;

    #[ORM\Column]
    private ?float $alertRate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getCurrency(): ?Currencies
    {
        return $this->currencies;
    }

    public function setCurrency(?Currencies $currencies): static
    {
        $this->currencies = $currencies;
        return $this;
    }

    public function getAlertRate(): ?float
    {
        return $this->alertRate;
    }

    public function setAlertRate(float $alertRate): static
    {
        $this->alertRate = $alertRate;

        return $this;
    }
}