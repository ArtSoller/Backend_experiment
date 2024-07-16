<?php

namespace App\Infrastructure\Database\Repository;

use App\Infrastructure\Database\Entity\Alerts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AlertsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Alerts::class);
    }
}