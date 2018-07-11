<?php

namespace Ivoz\Provider\Infrastructure\Persistence\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Ivoz\Provider\Domain\Model\CallCsvScheduler\CallCsvScheduler;
use Ivoz\Provider\Domain\Model\CallCsvScheduler\CallCsvSchedulerRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CallCsvSchedulerDoctrineRepository extends ServiceEntityRepository implements CallCsvSchedulerRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CallCsvScheduler::class);
    }
}
