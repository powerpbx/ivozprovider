<?php

namespace Ivoz\Provider\Domain\Model\CallCsv;

use Ivoz\Core\Application\DataTransferObjectInterface;
use Ivoz\Core\Application\ForeignKeyTransformerInterface;
use Ivoz\Core\Application\CollectionTransformerInterface;
use Ivoz\Core\Application\Model\DtoNormalizer;

/**
 * @codeCoverageIgnore
 */
abstract class CallCsvDtoAbstract implements DataTransferObjectInterface
{
    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var string
     */
    private $email;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $csvFileSize;

    /**
     * @var string
     */
    private $csvMimeType;

    /**
     * @var string
     */
    private $csvBaseName;

    /**
     * @var \Ivoz\Provider\Domain\Model\Company\CompanyDto | null
     */
    private $company;

    /**
     * @var \Ivoz\Provider\Domain\Model\CallCsvScheduler\CallCsvSchedulerDto | null
     */
    private $scheduler;


    use DtoNormalizer;

    public function __construct($id = null)
    {
        $this->setId($id);
    }

    /**
     * @inheritdoc
     */
    public static function getPropertyMap(string $context = '')
    {
        if ($context === self::CONTEXT_COLLECTION) {
            return ['id' => 'id'];
        }

        return [
            'createdAt' => 'createdAt',
            'email' => 'email',
            'id' => 'id',
            'csv' => ['fileSize','mimeType','baseName'],
            'companyId' => 'company',
            'schedulerId' => 'scheduler'
        ];
    }

    /**
     * @return array
     */
    public function toArray($hideSensitiveData = false)
    {
        return [
            'createdAt' => $this->getCreatedAt(),
            'email' => $this->getEmail(),
            'id' => $this->getId(),
            'csv' => [
                'fileSize' => $this->getCsvFileSize(),
                'mimeType' => $this->getCsvMimeType(),
                'baseName' => $this->getCsvBaseName()
            ],
            'company' => $this->getCompany(),
            'scheduler' => $this->getScheduler()
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function transformForeignKeys(ForeignKeyTransformerInterface $transformer)
    {
        $this->company = $transformer->transform('Ivoz\\Provider\\Domain\\Model\\Company\\Company', $this->getCompanyId());
        $this->scheduler = $transformer->transform('Ivoz\\Provider\\Domain\\Model\\CallCsvScheduler\\CallCsvScheduler', $this->getSchedulerId());
    }

    /**
     * {@inheritDoc}
     */
    public function transformCollections(CollectionTransformerInterface $transformer)
    {

    }

    /**
     * @param \DateTime $createdAt
     *
     * @return static
     */
    public function setCreatedAt($createdAt = null)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param string $email
     *
     * @return static
     */
    public function setEmail($email = null)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param integer $id
     *
     * @return static
     */
    public function setId($id = null)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $csvFileSize
     *
     * @return static
     */
    public function setCsvFileSize($csvFileSize = null)
    {
        $this->csvFileSize = $csvFileSize;

        return $this;
    }

    /**
     * @return integer
     */
    public function getCsvFileSize()
    {
        return $this->csvFileSize;
    }

    /**
     * @param string $csvMimeType
     *
     * @return static
     */
    public function setCsvMimeType($csvMimeType = null)
    {
        $this->csvMimeType = $csvMimeType;

        return $this;
    }

    /**
     * @return string
     */
    public function getCsvMimeType()
    {
        return $this->csvMimeType;
    }

    /**
     * @param string $csvBaseName
     *
     * @return static
     */
    public function setCsvBaseName($csvBaseName = null)
    {
        $this->csvBaseName = $csvBaseName;

        return $this;
    }

    /**
     * @return string
     */
    public function getCsvBaseName()
    {
        return $this->csvBaseName;
    }

    /**
     * @param \Ivoz\Provider\Domain\Model\Company\CompanyDto $company
     *
     * @return static
     */
    public function setCompany(\Ivoz\Provider\Domain\Model\Company\CompanyDto $company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return \Ivoz\Provider\Domain\Model\Company\CompanyDto
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param integer $id | null
     *
     * @return static
     */
    public function setCompanyId($id)
    {
        $value = !is_null($id)
            ? new \Ivoz\Provider\Domain\Model\Company\CompanyDto($id)
            : null;

        return $this->setCompany($value);
    }

    /**
     * @return integer | null
     */
    public function getCompanyId()
    {
        if ($dto = $this->getCompany()) {
            return $dto->getId();
        }

        return null;
    }

    /**
     * @param \Ivoz\Provider\Domain\Model\CallCsvScheduler\CallCsvSchedulerDto $scheduler
     *
     * @return static
     */
    public function setScheduler(\Ivoz\Provider\Domain\Model\CallCsvScheduler\CallCsvSchedulerDto $scheduler = null)
    {
        $this->scheduler = $scheduler;

        return $this;
    }

    /**
     * @return \Ivoz\Provider\Domain\Model\CallCsvScheduler\CallCsvSchedulerDto
     */
    public function getScheduler()
    {
        return $this->scheduler;
    }

    /**
     * @param integer $id | null
     *
     * @return static
     */
    public function setSchedulerId($id)
    {
        $value = !is_null($id)
            ? new \Ivoz\Provider\Domain\Model\CallCsvScheduler\CallCsvSchedulerDto($id)
            : null;

        return $this->setScheduler($value);
    }

    /**
     * @return integer | null
     */
    public function getSchedulerId()
    {
        if ($dto = $this->getScheduler()) {
            return $dto->getId();
        }

        return null;
    }
}


