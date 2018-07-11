<?php

namespace Ivoz\Provider\Domain\Model\CallCsvScheduler;

/**
 * CallCsvScheduler
 */
class CallCsvScheduler extends CallCsvSchedulerAbstract implements CallCsvSchedulerInterface
{
    use CallCsvSchedulerTrait;

    /**
     * @codeCoverageIgnore
     * @return array
     */
    public function getChangeSet()
    {
        return parent::getChangeSet();
    }

    /**
     * Get id
     * @codeCoverageIgnore
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}

