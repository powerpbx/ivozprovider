<?php

namespace Ivoz\Provider\Infrastructure\Persistence\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Ivoz\Provider\Domain\Model\ConferenceRoom\ConferenceRoomRepository;
use Ivoz\Provider\Domain\Model\ConferenceRoom\ConferenceRoom;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * ConferenceRoomDoctrineRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ConferenceRoomDoctrineRepository extends ServiceEntityRepository implements ConferenceRoomRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ConferenceRoom::class);
    }
}
