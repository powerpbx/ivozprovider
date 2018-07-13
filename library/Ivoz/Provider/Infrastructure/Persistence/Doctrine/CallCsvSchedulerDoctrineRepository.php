<?php

namespace Ivoz\Provider\Infrastructure\Persistence\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Ivoz\Provider\Domain\Model\CallCsvScheduler\CallCsvScheduler;
use Ivoz\Provider\Domain\Model\CallCsvScheduler\CallCsvSchedulerInterface;
use Ivoz\Provider\Domain\Model\CallCsvScheduler\CallCsvSchedulerRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CallCsvSchedulerDoctrineRepository extends ServiceEntityRepository implements CallCsvSchedulerRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CallCsvScheduler::class);
    }

    /**
     * @return CallCsvSchedulerInterface[]
     */
    public function getPendingSchedulers()
    {
        $now = new \DateTime(
            null,
            new \DateTimeZone('UTC')
        );

        // nextExecution
        $qb = $this->createQueryBuilder('self');
        $nextExecutionCondition =
            $qb
                ->expr()
                ->lte(
                    'self.nextExecution',
                    "'" . $now->format('Y-m-d H:i:s') . "'"
                );

        $query = $qb
            ->select('self')
            ->where($nextExecutionCondition)
            ->getQuery();

        return $query->getResult();
    }
}
