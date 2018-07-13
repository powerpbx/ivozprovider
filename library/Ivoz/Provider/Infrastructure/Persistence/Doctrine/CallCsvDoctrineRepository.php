<?php

namespace Ivoz\Provider\Infrastructure\Persistence\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Ivoz\Provider\Domain\Model\CallCsv\CallCsv;
use Ivoz\Provider\Domain\Model\CallCsv\CallCsvInterface;
use Ivoz\Provider\Domain\Model\CallCsv\CallCsvRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CallCsvDoctrineRepository extends ServiceEntityRepository implements CallCsvRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CallCsv::class);
    }
}
