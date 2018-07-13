<?php

namespace Ivoz\Provider\Domain\Service\CallCsvScheduler;

use Ivoz\Core\Domain\Service\LifecycleEventHandlerInterface;
use Ivoz\Provider\Domain\Model\CallCsvScheduler\CallCsvSchedulerInterface;

/**
 * Class NextExecutionResolver
 */
class NextExecutionResolver implements CallCsvSchedulerLifecycleEventHandlerInterface
{
    const PRE_PERSIST_PRIORITY = LifecycleEventHandlerInterface::PRIORITY_NORMAL;

    public function __construct() {}

    public static function getSubscribedEvents()
    {
        return [
            self::EVENT_PRE_PERSIST => self::PRE_PERSIST_PRIORITY,
        ];
    }

    public function execute(CallCsvSchedulerInterface $scheduler, bool $isNew)
    {
        $nextExecution = $scheduler->getNextExecution();
        if (!$nextExecution) {
            $this->setFallbackNextExecution($scheduler);
            return;
        }

        $this->updateNextExecution($scheduler);
    }

    /**
     * @see http://php.net/manual/es/datetime.formats.relative.php
     * @param CallCsvSchedulerInterface $scheduler
     */
    protected function setFallbackNextExecution(CallCsvSchedulerInterface $scheduler)
    {
        $frecuency = $scheduler->getFrequency() -1;
        $unit = $scheduler->getUnit();

        $timeZone = $scheduler->getCompany()->getDefaultTimezone();
        $dateTimeZone = new \DateTimeZone($timeZone->getTz());

        $nextExecution = new \DateTime(
            null,
            $dateTimeZone
        );
        $nextExecution->modify("+${frecuency} ${unit}s");

        switch ($unit) {
            case 'year':
            case 'week':
                $nextExecution->modify('next monday');
                break;
            case 'month':
                $nextExecution->modify('next month');
                break;
            default:
                throw new \DomainException('Unkown unit ' . $unit);
        }


        $nextExecution->setTime(8, 0, 0);
        $scheduler->setNextExecution(
            $nextExecution
        );
    }

    /**
     * @param CallCsvSchedulerInterface $scheduler
     */
    protected function updateNextExecution(CallCsvSchedulerInterface $scheduler)
    {
        if (!$scheduler->hasChanged('lastExecution')) {
            return;
        }

        if ($scheduler->hasChanged('nextExecution')) {
            // Manually updated
            return;
        }

        $nextExecution = clone $scheduler->getNextExecution();
        if (!$nextExecution) {
            return;
        }
        $nextExecution->setTimezone(
            new \DateTimeZone('UTC')
        );

        $nextExecution
            ->add(
                $scheduler->getInterval()
            );

        $scheduler
            ->setNextExecution($nextExecution);
    }
}