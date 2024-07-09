<?php

namespace App\Infrastructure\Database\Entity;
use App\Infrastructure\Database\Repository\GenerationRulesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GenerationRulesRepository::class)]
class GenerationRules
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $maxRate = null;

    #[ORM\Column]
    private ?float $minRate = null;

    #[ORM\ManyToOne(targetEntity: Currencies::class)]
    #[ORM\JoinColumn(name: 'currency_id', referencedColumnName: 'currency_id', nullable: false)]
    private ?Currencies $currency = null;

    #[ORM\OneToMany(mappedBy: 'generationRule', targetEntity: Users::class)]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMaxRate(): ?float
    {
        return $this->maxRate;
    }

    public function setMaxRate(float $maxRate): static
    {
        $this->maxRate = $maxRate;
        return $this;
    }

    public function getMinRate(): ?float
    {
        return $this->minRate;
    }

    public function setMinRate(float $minRate): static
    {
        $this->minRate = $minRate;
        return $this;
    }

    public function getCurrency(): ?Currencies
    {
        return $this->currency;
    }

    public function setCurrency(Currencies $currency): static
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return Collection<int, Users>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Users $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setGenerationRule($this);
        }

        return $this;
    }

    public function removeUser(Users $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getGenerationRule() === $this) {
                $user->setGenerationRule(null);
            }
        }

        return $this;
    }
}