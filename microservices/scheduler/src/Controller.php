<?php

use Symfony\Component\HttpFoundation\Response;
use Ivoz\Provider\Domain\Service\Invoice\CreateByScheduler;
use Ivoz\Provider\Domain\Service\CallCsv\CreateByScheduler as CreateCallCsvByScheduler;
use Ivoz\Provider\Domain\Model\InvoiceScheduler\InvoiceSchedulerRepository;
use Ivoz\Provider\Domain\Model\CallCsvScheduler\CallCsvSchedulerRepository;

class Controller
{
    /**
     * @var CreateByScheduler
     */
    private $invoiceCreator;

    /**
     * @var InvoiceSchedulerRepository
     */
    private $invoiceSchedulerRepository;

    /**
     * @var CallCsvSchedulerRepository
     */
    private $callCsvSchedulerRepository;

    /**
     * @var array
     */
    private $errors = [];

    public function __construct(
        CreateByScheduler $invoiceCreator,
        InvoiceSchedulerRepository $invoiceSchedulerRepository,
        CallCsvSchedulerRepository $callCsvSchedulerRepository,
        CreateCallCsvByScheduler $createCallCsvByScheduler
    ) {
        $this->invoiceSchedulerRepository = $invoiceSchedulerRepository;
        $this->invoiceCreator = $invoiceCreator;
        $this->callCsvSchedulerRepository = $callCsvSchedulerRepository;
        $this->createCallCsvByScheduler = $createCallCsvByScheduler;
    }

    public function indexAction()
    {
        $this->invoiceAction();
        $this->callCsvAction();

        return $this->createResponse();
    }

    /**
     * @return Response
     */
    private function createResponse()
    {
        if (count($this->errors) === 0) {
            return new Response("Done!\n", 200);
        }

        return new Response(
            implode("\n", $this->errors),
            500
        );
    }

    protected function invoiceAction()
    {
        $invoiceSchedulers = $this->invoiceSchedulerRepository->getPendingSchedulers();

        foreach ($invoiceSchedulers as $invoiceScheduler) {
            try {
                $this->invoiceCreator->execute($invoiceScheduler);
            } catch (\Exception $e) {
                $this->errors[] = $e->getMessage();
            }
        }
    }

    protected function callCsvAction()
    {
        $callCsvSchedulers = $this->callCsvSchedulerRepository->getPendingSchedulers();

        foreach ($callCsvSchedulers as $callCsvScheduler) {
            try {
                $this->createCallCsvByScheduler->execute($callCsvScheduler);
            } catch (\Exception $e) {
                $this->errors[] = $e->getMessage();
            }
        }
    }
}