<?php

namespace Ivoz\Provider\Domain\Model\DestinationRateGroup;

use Ivoz\Core\Application\DataTransferObjectInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;

/**
 * DestinationRateGroupTrait
 * @codeCoverageIgnore
 */
trait DestinationRateGroupTrait
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var Collection
     */
    protected $destinationRates;


    /**
     * Constructor
     */
    protected function __construct()
    {
        parent::__construct(...func_get_args());
        $this->destinationRates = new ArrayCollection();
    }

    /**
     * Factory method
     * @internal use EntityTools instead
     * @param DataTransferObjectInterface $dto
     * @param \Ivoz\Core\Application\ForeignKeyTransformerInterface  $fkTransformer
     * @return self
     */
    public static function fromDto(
        DataTransferObjectInterface $dto,
        \Ivoz\Core\Application\ForeignKeyTransformerInterface $fkTransformer
    ) {
        /**
         * @var $dto DestinationRateGroupDto
         */
        $self = parent::fromDto($dto, $fkTransformer);
        if (!is_null($dto->getDestinationRates())) {
            $self->replaceDestinationRates(
                $fkTransformer->transformCollection(
                    $dto->getDestinationRates()
                )
            );
        }
        if ($dto->getId()) {
            $self->id = $dto->getId();
            $self->initChangelog();
        }

        return $self;
    }

    /**
     * @internal use EntityTools instead
     * @param DataTransferObjectInterface $dto
     * @param \Ivoz\Core\Application\ForeignKeyTransformerInterface  $fkTransformer
     * @return self
     */
    public function updateFromDto(
        DataTransferObjectInterface $dto,
        \Ivoz\Core\Application\ForeignKeyTransformerInterface $fkTransformer
    ) {
        /**
         * @var $dto DestinationRateGroupDto
         */
        parent::updateFromDto($dto, $fkTransformer);
        if (!is_null($dto->getDestinationRates())) {
            $this->replaceDestinationRates(
                $fkTransformer->transformCollection(
                    $dto->getDestinationRates()
                )
            );
        }
        return $this;
    }

    /**
     * @internal use EntityTools instead
     * @param int $depth
     * @return DestinationRateGroupDto
     */
    public function toDto($depth = 0)
    {
        $dto = parent::toDto($depth);
        return $dto
            ->setId($this->getId());
    }

    /**
     * @return array
     */
    protected function __toArray()
    {
        return parent::__toArray() + [
            'id' => self::getId()
        ];
    }
    /**
     * Add destinationRate
     *
     * @param \Ivoz\Provider\Domain\Model\DestinationRate\DestinationRateInterface $destinationRate
     *
     * @return DestinationRateGroupTrait
     */
    public function addDestinationRate(\Ivoz\Provider\Domain\Model\DestinationRate\DestinationRateInterface $destinationRate)
    {
        $this->destinationRates->add($destinationRate);

        return $this;
    }

    /**
     * Remove destinationRate
     *
     * @param \Ivoz\Provider\Domain\Model\DestinationRate\DestinationRateInterface $destinationRate
     */
    public function removeDestinationRate(\Ivoz\Provider\Domain\Model\DestinationRate\DestinationRateInterface $destinationRate)
    {
        $this->destinationRates->removeElement($destinationRate);
    }

    /**
     * Replace destinationRates
     *
     * @param \Ivoz\Provider\Domain\Model\DestinationRate\DestinationRateInterface[] $destinationRates
     * @return self
     */
    public function replaceDestinationRates(Collection $destinationRates)
    {
        $updatedEntities = [];
        $fallBackId = -1;
        foreach ($destinationRates as $entity) {
            $index = $entity->getId() ? $entity->getId() : $fallBackId--;
            $updatedEntities[$index] = $entity;
            $entity->setDestinationRateGroup($this);
        }
        $updatedEntityKeys = array_keys($updatedEntities);

        foreach ($this->destinationRates as $key => $entity) {
            $identity = $entity->getId();
            if (in_array($identity, $updatedEntityKeys)) {
                $this->destinationRates->set($key, $updatedEntities[$identity]);
            } else {
                $this->destinationRates->remove($key);
            }
            unset($updatedEntities[$identity]);
        }

        foreach ($updatedEntities as $entity) {
            $this->addDestinationRate($entity);
        }

        return $this;
    }

    /**
     * Get destinationRates
     *
     * @return \Ivoz\Provider\Domain\Model\DestinationRate\DestinationRateInterface[]
     */
    public function getDestinationRates(Criteria $criteria = null)
    {
        if (!is_null($criteria)) {
            return $this->destinationRates->matching($criteria)->toArray();
        }

        return $this->destinationRates->toArray();
    }
}
