<?php

namespace App\Infrastructure\Database\Entity;

use App\Infrastructure\Database\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
class Users
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_user = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\JoinTable(name: 'users_roles')]
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id_user')]
    #[ORM\InverseJoinColumn(name: 'id_role', referencedColumnName: 'id_role')]
    #[ORM\ManyToMany(targetEntity: Roles::class)]
    private ArrayCollection $users_roles;

    public function getId_user(): ?int
    {
        return $this->id_user;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getUsersRoles(): ArrayCollection
    {
        return $this->users_roles;
    }

    public function setUsersRoles(ArrayCollection $users_roles): static
    {
        $this->users_roles = $users_roles;

        return $this;
    }
}
