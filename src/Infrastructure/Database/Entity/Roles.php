<?php

namespace App\Infrastructure\Database\Entity;

use App\Infrastructure\Database\Repository\RolesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RolesRepository::class)]
class Roles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_role = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    public function getId_role(): ?int
    {
        return $this->id_role;
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
}
