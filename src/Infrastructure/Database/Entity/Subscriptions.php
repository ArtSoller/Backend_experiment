<?php

namespace App\Infrastructure\Database\Entity;

use App\Infrastructure\Database\Repository\SubscriptionsRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubscriptionsRepository::class)]
class Subscriptions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $sub_id = null;

    #[ORM\Column(length: 255)]
    private ?float $cost = null;

    #[ORM\Column]
    private ?int $duration = null;

//    #[ORM\OneToMany(targetEntity: User::class, mappedBy: "subscription")]
//    private Collection $users;

//    public function __construct()
//    {
//        $this->users = new ArrayCollection();
//    }

    public function getSubscriptionId(): ?int
    {
        return $this->sub_id;
    }

    public function getCost(): ?float
    {
        return $this->cost;
    }

    public function setCost(string $cost): static
    {
        $this->cost = $cost;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(string $duration): static
    {
        $this->duration = $duration;

        return $this;
    }
}