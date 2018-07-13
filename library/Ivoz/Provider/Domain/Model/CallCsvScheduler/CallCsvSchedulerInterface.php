<?php

namespace Ivoz\Provider\Domain\Model\CallCsvScheduler;

use Ivoz\Core\Domain\Model\LoggableEntityInterface;

interface CallCsvSchedulerInterface extends LoggableEntityInterface
{
    /**
     * @codeCoverageIgnore
     * @return array
     */
    public function getChangeSet();

    /**
     * @return \DateInterval
     */
    public function getInterval();

    /**
     * Set name
     *
     * @param string $name
     *
     * @return self
     */
    public function setName($name);

    /**
     * Get name
     *
     * @return string
     */
    public function getName();

    /**
     * Set unit
     *
     * @param string $unit
     *
     * @return self
     */
    public function setUnit($unit);

    /**
     * Get unit
     *
     * @return string
     */
    public function getUnit();

    /**
     * Set frequency
     *
     * @param integer $frequency
     *
     * @return self
     */
    public function setFrequency($frequency);

    /**
     * Get frequency
     *
     * @return integer
     */
    public function getFrequency();

    /**
     * Set email
     *
     * @param string $email
     *
     * @return self
     */
    public function setEmail($email);

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail();

    /**
     * Set lastExecution
     *
     * @param \DateTime $lastExecution
     *
     * @return self
     */
    public function setLastExecution($lastExecution = null);

    /**
     * Get lastExecution
     *
     * @return \DateTime
     */
    public function getLastExecution();

    /**
     * Set nextExecution
     *
     * @param \DateTime $nextExecution
     *
     * @return self
     */
    public function setNextExecution($nextExecution = null);

    /**
     * Get nextExecution
     *
     * @return \DateTime
     */
    public function getNextExecution();

    /**
     * Set company
     *
     * @param \Ivoz\Provider\Domain\Model\Company\CompanyInterface $company
     *
     * @return self
     */
    public function setCompany(\Ivoz\Provider\Domain\Model\Company\CompanyInterface $company);

    /**
     * Get company
     *
     * @return \Ivoz\Provider\Domain\Model\Company\CompanyInterface
     */
    public function getCompany();

}

