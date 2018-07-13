<?php

namespace Ivoz\Provider\Domain\Service\CallCsv;

use Ivoz\Core\Application\Service\EntityTools;
use Ivoz\Provider\Domain\Model\CallCsv\CallCsvDto;
use Ivoz\Provider\Domain\Model\CallCsv\CallCsvInterface;
use Ivoz\Provider\Domain\Model\CallCsvScheduler\CallCsvSchedulerInterface;
use Psr\Log\LoggerInterface;

class CreateByScheduler
{
    /**
     * @var EntityTools
     */
    private $entityTools;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(
        EntityTools $entityTools,
        LoggerInterface $logger
    ) {
        $this->entityTools = $entityTools;
        $this->logger = $logger;
    }

    /**
     * @throws \DomainException
     */
    public function execute(CallCsvSchedulerInterface $scheduler)
    {
        try {
            $this->createCallCsv($scheduler);
            $this->updateLastExecutionDate($scheduler);
        } catch (\Exception $e) {

            $name = $scheduler->getName();
            $this->logger->error(
                "Call CSV scheduler #${$name} has failed: "
                . $e->getMessage()
            );

            throw $e;
        }
    }

    /**
     * @param CallCsvSchedulerInterface $scheduler
     * @return CallCsvInterface
     */
    private function createCallCsv(CallCsvSchedulerInterface $scheduler)
    {
        $tmpFilePath = tempnam('/tmp', 'csv');

        $callCsvDto = new CallCsvDto();
        $callCsvDto
            ->setCreatedAt(new \DateTime())
            ->setEmail(
                $scheduler->getEmail()
            )
            ->setCsvPath($tmpFilePath)
            ->setCompanyId(
                $scheduler->getCompany()->getId()
            )->setSchedulerId(
                $scheduler->getId()
            );

        return $this->entityTools->persistDto(
            $callCsvDto,
            null,
            true
        );
    }

    /**
     * @param CallCsvSchedulerInterface $scheduler
     * @return void
     */
    private function updateLastExecutionDate(CallCsvSchedulerInterface $scheduler)
    {
        $invoiceSchedulerDto = $this
            ->entityTools
            ->entityToDto($scheduler);

        $invoiceSchedulerDto->setLastExecution(
            new \DateTime()
        );

        $this->entityTools->persistDto(
            $invoiceSchedulerDto,
            $scheduler,
            true
        );
    }
}