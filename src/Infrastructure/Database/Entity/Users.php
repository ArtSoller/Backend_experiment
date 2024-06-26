<?php

namespace App\Infrastructure\Database\Entity;

use App\Infrastructure\Database\Repository\UsersRepository;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private ?Ulid $user_id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\JoinTable(name: 'user_roles')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'user_id')]
    #[ORM\InverseJoinColumn(name: 'role_id', referencedColumnName: 'role_id')]
    #[ORM\ManyToMany(targetEntity: Roles::class)]
    private Collection $user_roles;

    #[ORM\JoinTable(name: 'alerts')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'user_id')]
    #[ORM\InverseJoinColumn(name: 'currency_id', referencedColumnName: 'currency_id')]
    #[ORM\ManyToMany(targetEntity: Currencies::class)]
    private Collection $alerts;

//    #[ORM\ManyToOne(targetEntity: Subscriptions::class, inversedBy: "users")]
//    #[ORM\JoinColumn(name: "subscription_id", referencedColumnName: "sub_id", nullable: true)]
//    private Collection $subscription;

    public function __construct()
    {
        $this->user_roles = new ArrayCollection();
        $this->user_alerts = new ArrayCollection();
//        $this->subscription = new ArrayCollection();
    }
    public function getUserId(): ?Ulid
    {
        return $this->user_id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getUserRoles(): Collection
    {
        return $this->user_roles;
    }
    public function getRoles(): array
    {
        $roles = $this->getUserRoles()->map(function($role) {
            return $role->getName();
        })->toArray();

        $roles[] = 'ROLE_USER';

        return $roles;
    }

    public function setUserRoles(Roles $user_role): static
    {
        if (!$this->user_roles->contains($user_role)) {
            $this->user_roles->add($user_role);
        }

        return $this;
    }

    public function getAlerts(): ArrayCollection
    {
        return $this->alerts;
    }

    public function setAlerts(ArrayCollection $alerts): static
    {
        $this->alerts = $alerts;

        return $this;
    }

//    public function getSubscription(): ArrayCollection
//    {
//        return $this->subscription;
//    }
//
//    public function setSubscription(ArrayCollection $subscription): static
//    {
//        $this->subscription = $subscription;
//
//        return $this;
//    }


    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}