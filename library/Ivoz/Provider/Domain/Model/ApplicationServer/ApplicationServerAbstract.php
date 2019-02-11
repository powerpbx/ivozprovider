<?php

namespace Ivoz\Provider\Domain\Model\ApplicationServer;

use Assert\Assertion;
use Ivoz\Core\Application\DataTransferObjectInterface;
use Ivoz\Core\Domain\Model\ChangelogTrait;
use Ivoz\Core\Domain\Model\EntityInterface;

/**
 * ApplicationServerAbstract
 * @codeCoverageIgnore
 */
abstract class ApplicationServerAbstract
{
    /**
     * @var string
     */
    protected $ip;

    /**
     * @var string | null
     */
    protected $name;

    /**
     * @var integer
     */
    protected $grpid = 1;

    use ChangelogTrait;

    /**
     * Constructor
     */
    protected function __construct($ip)
    {
        $this->setIp($ip);
    }

    abstract public function getId();

    public function __toString()
    {
        return sprintf(
            "%s#%s",
            "ApplicationServer",
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
     * @return ApplicationServerDto
     */
    public static function createDto($id = null)
    {
        return new ApplicationServerDto($id);
    }

    /**
     * @internal use EntityTools instead
     * @param EntityInterface|null $entity
     * @param int $depth
     * @return ApplicationServerDto|null
     */
    public static function entityToDto(EntityInterface $entity = null, $depth = 0)
    {
        if (!$entity) {
            return null;
        }

        Assertion::isInstanceOf($entity, ApplicationServerInterface::class);

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
     * @internal use EntityTools instead
     * @param DataTransferObjectInterface $dto
     * @return self
     */
    public static function fromDto(
        DataTransferObjectInterface $dto,
        \Ivoz\Core\Application\ForeignKeyTransformerInterface $fkTransformer
    ) {
        /**
         * @var $dto ApplicationServerDto
         */
        Assertion::isInstanceOf($dto, ApplicationServerDto::class);

        $self = new static(
            $dto->getIp(),
            $dto->getGrpid()
        );

        $self
            ->setName($dto->getName())
        ;

        $self->sanitizeValues();
        $self->initChangelog();

        return $self;
    }

    /**
     * @internal use EntityTools instead
     * @param DataTransferObjectInterface $dto
     * @return self
     */
    public function updateFromDto(
        DataTransferObjectInterface $dto,
        \Ivoz\Core\Application\ForeignKeyTransformerInterface $fkTransformer
    ) {
        /**
         * @var $dto ApplicationServerDto
         */
        Assertion::isInstanceOf($dto, ApplicationServerDto::class);

        $this
            ->setIp($dto->getIp())
            ->setName($dto->getName())
            ->setGrpid($dto->getGrpid());


        $this->sanitizeValues();
        return $this;
    }

    /**
     * @internal use EntityTools instead
     * @param int $depth
     * @return ApplicationServerDto
     */
    public function toDto($depth = 0)
    {
        return self::createDto()
            ->setIp(self::getIp())
            ->setName(self::getName())
            ->setGrpid(self::getGrpid());
    }

    /**
     * @return array
     */
    protected function __toArray()
    {
        return [
            'ip' => self::getIp(),
            'name' => self::getName(),
            'grpid' => self::getGrpid()
        ];
    }
    // @codeCoverageIgnoreStart

    /**
     * Set ip
     *
     * @param string $ip
     *
     * @return self
     */
    protected function setIp($ip)
    {
        Assertion::notNull($ip, 'ip value "%s" is null, but non null value was expected.');
        Assertion::maxLength($ip, 50, 'ip value "%s" is too long, it should have no more than %d characters, but has %d characters.');

        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return self
     */
    protected function setName($name = null)
    {
        if (!is_null($name)) {
            Assertion::maxLength($name, 64, 'name value "%s" is too long, it should have no more than %d characters, but has %d characters.');
        }

        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string | null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set grpid
     *
     * @param integer $grpid
     *
     * @return self
     */
    protected function setGrpid($grpid)
    {
        Assertion::notNull($grpid, 'grpid value "%s" is null, but non null value was expected.');
        Assertion::integerish($grpid, 'grpid value "%s" is not an integer or a number castable to integer.');
        Assertion::greaterOrEqualThan($grpid, 0, 'grpid provided "%s" is not greater or equal than "%s".');
         $this->grpid = $grpid;
         return $this;
    }
     /**
     * Get grpid
     *
     * @return integer
     */
    public function getGrpid()
    {
        return $this->grpid;
    }

    // @codeCoverageIgnoreEnd
}
