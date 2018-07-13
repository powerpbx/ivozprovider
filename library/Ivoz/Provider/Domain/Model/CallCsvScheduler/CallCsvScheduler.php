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

    /**
     * @return \DateInterval
     */
    public function getInterval()
    {
        $frecuency = $this->getFrequency();

        switch ($this->getUnit()) {
            /** @see http://php.net/manual/es/dateinterval.createfromdatestring.php */
            case 'year':
                return new \DateInterval("P${frecuency}Y");
            case 'month':
                return new \DateInterval("P${frecuency}M");
            case 'week':
                return new \DateInterval("P${frecuency}W");
        }
    }
}

