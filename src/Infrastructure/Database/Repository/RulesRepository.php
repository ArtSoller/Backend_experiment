<?php

namespace App\Infrastructure\Database\Repository;

use App\Infrastructure\Database\Entity\Rules;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RulesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rules::class);
    }
}