<?php

namespace Ivoz\Provider\Domain\Service\CallCsv;

use Ivoz\Core\Application\Service\EntityTools;
use Ivoz\Provider\Domain\Model\CallCsv\CallCsvDto;
use Ivoz\Provider\Domain\Model\CallCsv\CallCsvInterface;
use Ivoz\Provider\Domain\Model\NotificationTemplate\NotificationTemplateRepository;
use PhpMimeMailParser\Parser;

class EmailSender implements CallCsvLifecycleEventHandlerInterface
{
    const ON_COMMIT_PRIORITY = CallCsvLifecycleEventHandlerInterface::PRIORITY_LOW;

    /**
     * @var EntityTools
     */
    protected $entityTools;

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var NotificationTemplateRepository
     */
    protected $notificationTemplateRepository;

    /**
     * Sender constructor.
     * @param EntityTools $entityTools
     * @param \Swift_Mailer $mailer
     */
    public function __construct(
        EntityTools $entityTools,
        \Swift_Mailer $mailer,
        NotificationTemplateRepository $notificationTemplateRepository
    ) {
        $this->entityTools = $entityTools;
        $this->mailer = $mailer;
        $this->notificationTemplateRepository = $notificationTemplateRepository;
    }

    public static function getSubscribedEvents()
    {
        return [
            self::EVENT_ON_COMMIT => self::ON_COMMIT_PRIORITY
        ];
    }

    public function execute(CallCsvInterface $invoice)
    {
//        $targetEmail = $this->getTargetEmail($invoice);
//        if (!$targetEmail) {
//            return false;
//        }
//
//        $notificationTemplateContent = $this->getNotificationTemplateContent($invoice);
//        // Get data from template
//        $fromName = $notificationTemplateContent->getFromName();
//        $fromAddress = $notificationTemplateContent->getFromAddress();
//        $body = $this->parseVariables(
//            $invoice,
//            $notificationTemplateContent->getBody()
//        );
//        $subject = $this->parseVariables(
//            $invoice,
//            $notificationTemplateContent->getSubject()
//        );
//
//        /** @var CallCsvDto $invoiceDto */
//        $invoiceDto = $this->entityTools->entityToDto($invoice);
//        $pdf = \Swift_Attachment::fromPath(
//            $invoiceDto->getPdfPath(),
//            'application/pdf'
//        );
//        $pdf->setFilename(
//            $invoiceDto->getPdfBaseName()
//        );
//
//        // Create a new mail and attach the PDF file
//        $mail = new \Swift_Message();
//        $mail->setBody($body, 'text/html')
//            ->setSubject($subject)
//            ->setFrom($fromAddress, $fromName)
//            ->setTo($targetEmail)
//            ->attach($pdf);
//
//        // Send the email
//        $this->mailer->send($mail);
    }

    /**
     * @param CallCsvInterface $invoice
     * @return bool|string
     */
    private function getTargetEmail(CallCsvInterface $invoice)
    {
        if (!$invoice->hasChanged('status')) {
            return false;
        }

        if ($invoice->getStatus() !== 'created') {
            return false;
        }

        $scheduler = $invoice->getScheduler();
        if (!$scheduler) {
            return false;
        }

        $email = $scheduler->getEmail();
        if (empty($email)) {
            return false;
        }

        return $email;
    }

    /**
     * @param CallCsvInterface $invoice
     * @return \Ivoz\Provider\Domain\Model\NotificationTemplateContent\NotificationTemplateContentInterface
     */
    private function getNotificationTemplateContent(CallCsvInterface $invoice)
    {
        $company = $invoice->getCompany();

        // Get Company Notification Template for faxes
        $invoiceNotificationTemplate = $company->getCallCsvNotificationTemplate();
        $genericCallCsvNotificationTemplate = $this->notificationTemplateRepository->findOneBy([
            'brand' => null,
            'type' => 'invoice'
        ]);

        if (!$invoiceNotificationTemplate) {
            // Get Generic Notification Template
            $invoiceNotificationTemplate = $genericCallCsvNotificationTemplate;
        }

        // Get Notification contents for required language
        $notificationTemplateContent = $invoiceNotificationTemplate->getContentsByLanguage(
            $company->getLanguage()
        );

        if (!$notificationTemplateContent) {
            // Fallback to generic template language content
            $notificationTemplateContent = $genericCallCsvNotificationTemplate->getContentsByLanguage(
                $company->getLanguage()
            );
        }

        return $notificationTemplateContent;
    }

    /**
     * @param CallCsvInterface $invoice
     * @param string $content
     * @return string
     */
    private function parseVariables(CallCsvInterface $invoice, string $content): string
    {
        $company = $invoice->getCompany();
        $timezone = $company->getDefaultTimezone();
        $dateTimeZone = new \DateTimeZone($timezone->getTz());

        $inDate = $invoice
            ->getInDate()
            ->setTimezone($dateTimeZone);

        $outDate = $invoice
            ->getOutDate()
            ->setTimezone($dateTimeZone);

        $substitution = [
            '${INVOICE_COMPANY}' => $company->getName(),
            '${INVOICE_DATE_IN}' => $inDate->format('Y-m-d'),
            '${INVOICE_DATE_OUT}' => $outDate->format('Y-m-d'),
            '${INVOICE_AMOUNT}' => $invoice->getTotalWithTax(),
            '${INVOICE_CURRENCY}' => '€'
        ];

        return str_replace(
            array_keys($substitution),
            array_values($substitution),
            $content
        );
    }
}
