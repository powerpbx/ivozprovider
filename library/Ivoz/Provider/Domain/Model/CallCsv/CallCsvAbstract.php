<?php

namespace Ivoz\Provider\Domain\Model\CallCsv;

use Assert\Assertion;
use Ivoz\Core\Application\DataTransferObjectInterface;
use Ivoz\Core\Domain\Model\ChangelogTrait;
use Ivoz\Core\Domain\Model\EntityInterface;

/**
 * CallCsvAbstract
 * @codeCoverageIgnore
 */
abstract class CallCsvAbstract
{
    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var Csv
     */
    protected $csv;

    /**
     * @var \Ivoz\Provider\Domain\Model\Company\CompanyInterface
     */
    protected $company;

    /**
     * @var \Ivoz\Provider\Domain\Model\CallCsvScheduler\CallCsvSchedulerInterface
     */
    protected $scheduler;


    use ChangelogTrait;

    /**
     * Constructor
     */
    protected function __construct($email, Csv $csv)
    {
        $this->setEmail($email);
        $this->setCsv($csv);
    }

    abstract public function getId();

    public function __toString()
    {
        return sprintf("%s#%s",
            "CallCsv",
            $this->getId()
        );
    }

    /**
     * @return void
     * @throws \Exception
     */
    protected function sanitizeValues()
    {
    }

    /**
     * @param null $id
     * @return CallCsvDto
     */
    public static function createDto($id = null)
    {
        return new CallCsvDto($id);
    }

    /**
     * @param EntityInterface|null $entity
     * @param int $depth
     * @return CallCsvDto|null
     */
    public static function entityToDto(EntityInterface $entity = null, $depth = 0)
    {
        if (!$entity) {
            return null;
        }

        Assertion::isInstanceOf($entity, CallCsvInterface::class);

        if ($depth < 1) {
            return static::createDto($entity->getId());
        }

        if ($entity instanceof \Doctrine\ORM\Proxy\Proxy && !$entity->__isInitialized()) {
            return static::createDto($entity->getId());
        }

        return $entity->toDto($depth-1);
    }

    /**
     * Factory method
     * @param DataTransferObjectInterface $dto
     * @return self
     */
    public static function fromDto(DataTransferObjectInterface $dto)
    {
        /**
         * @var $dto CallCsvDto
         */
        Assertion::isInstanceOf($dto, CallCsvDto::class);

        $csv = new Csv(
            $dto->getCsvFileSize(),
            $dto->getCsvMimeType(),
            $dto->getCsvBaseName()
        );

        $self = new static(
            $dto->getEmail(),
            $csv
        );

        $self
            ->setCreatedAt($dto->getCreatedAt())
            ->setCompany($dto->getCompany())
            ->setScheduler($dto->getScheduler())
        ;

        $self->sanitizeValues();
        $self->initChangelog();

        return $self;
    }

    /**
     * @param DataTransferObjectInterface $dto
     * @return self
     */
    public function updateFromDto(DataTransferObjectInterface $dto)
    {
        /**
         * @var $dto CallCsvDto
         */
        Assertion::isInstanceOf($dto, CallCsvDto::class);

        $csv = new Csv(
            $dto->getCsvFileSize(),
            $dto->getCsvMimeType(),
            $dto->getCsvBaseName()
        );

        $this
            ->setCreatedAt($dto->getCreatedAt())
            ->setEmail($dto->getEmail())
            ->setCsv($csv)
            ->setCompany($dto->getCompany())
            ->setScheduler($dto->getScheduler());



        $this->sanitizeValues();
        return $this;
    }

    /**
     * @param int $depth
     * @return CallCsvDto
     */
    public function toDto($depth = 0)
    {
        return self::createDto()
            ->setCreatedAt(self::getCreatedAt())
            ->setEmail(self::getEmail())
            ->setCsvFileSize(self::getCsv()->getFileSize())
            ->setCsvMimeType(self::getCsv()->getMimeType())
            ->setCsvBaseName(self::getCsv()->getBaseName())
            ->setCompany(\Ivoz\Provider\Domain\Model\Company\Company::entityToDto(self::getCompany(), $depth))
            ->setScheduler(\Ivoz\Provider\Domain\Model\CallCsvScheduler\CallCsvScheduler::entityToDto(self::getScheduler(), $depth));
    }

    /**
     * @return array
     */
    protected function __toArray()
    {
        return [
            'createdAt' => self::getCreatedAt(),
            'email' => self::getEmail(),
            'csvFileSize' => self::getCsv()->getFileSize(),
            'csvMimeType' => self::getCsv()->getMimeType(),
            'csvBaseName' => self::getCsv()->getBaseName(),
            'companyId' => self::getCompany() ? self::getCompany()->getId() : null,
            'schedulerId' => self::getScheduler() ? self::getScheduler()->getId() : null
        ];
    }


    // @codeCoverageIgnoreStart

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return self
     */
    public function setCreatedAt($createdAt = null)
    {
        if (!is_null($createdAt)) {
        $createdAt = \Ivoz\Core\Domain\Model\Helper\DateTimeHelper::createOrFix(
            $createdAt,
            null
        );
        }

        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return self
     */
    public function setEmail($email)
    {
        Assertion::notNull($email, 'email value "%s" is null, but non null value was expected.');
        Assertion::maxLength($email, 140, 'email value "%s" is too long, it should have no more than %d characters, but has %d characters.');

        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set company
     *
     * @param \Ivoz\Provider\Domain\Model\Company\CompanyInterface $company
     *
     * @return self
     */
    public function setCompany(\Ivoz\Provider\Domain\Model\Company\CompanyInterface $company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return \Ivoz\Provider\Domain\Model\Company\CompanyInterface
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set scheduler
     *
     * @param \Ivoz\Provider\Domain\Model\CallCsvScheduler\CallCsvSchedulerInterface $scheduler
     *
     * @return self
     */
    public function setScheduler(\Ivoz\Provider\Domain\Model\CallCsvScheduler\CallCsvSchedulerInterface $scheduler = null)
    {
        $this->scheduler = $scheduler;

        return $this;
    }

    /**
     * Get scheduler
     *
     * @return \Ivoz\Provider\Domain\Model\CallCsvScheduler\CallCsvSchedulerInterface
     */
    public function getScheduler()
    {
        return $this->scheduler;
    }

    /**
     * Set csv
     *
     * @param \Ivoz\Provider\Domain\Model\CallCsv\Csv $csv
     *
     * @return self
     */
    public function setCsv(Csv $csv)
    {
        $this->csv = $csv;

        return $this;
    }

    /**
     * Get csv
     *
     * @return \Ivoz\Provider\Domain\Model\CallCsv\Csv
     */
    public function getCsv()
    {
        return $this->csv;
    }

    // @codeCoverageIgnoreEnd
}

