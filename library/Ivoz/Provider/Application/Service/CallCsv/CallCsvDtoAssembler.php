<?php

namespace Ivoz\Provider\Application\Service\CallCsv;

use Ivoz\Core\Application\Service\StoragePathResolverCollection;
use Ivoz\Core\Domain\Model\EntityInterface;
use Ivoz\Core\Application\Service\Assembler\CustomDtoAssemblerInterface;
use Ivoz\Provider\Domain\Model\CallCsv\CallCsvDto;
use Ivoz\Provider\Domain\Model\CallCsv\CallCsvInterface;
use Assert\Assertion;

class CallCsvDtoAssembler implements CustomDtoAssemblerInterface
{
    /**
     * @var StoragePathResolverCollection
     */
    protected $storagePathResolver;

    public function __construct(
        StoragePathResolverCollection $storagePathResolver
    ) {
        $this->storagePathResolver = $storagePathResolver;
    }

    /**
     * @param CallCsvInterface $entity
     * @param integer $depth
     * @return CallCsvDto
     */
    public function toDto(EntityInterface $entity, $depth = 0)
    {
        Assertion::isInstanceOf($entity, CallCsvInterface::class);

        /** @var CallCsvDto $dto */
        $dto = $entity->toDto($depth);
        $id = $entity->getId();

        if (!$id) {
            return $dto;
        }

        if ($entity->getFile()->getFileSize()) {
            $pathResolver = $this
                ->storagePathResolver
                ->getPathResolver('Csv');

            $dto->setFilePath(
                $pathResolver->getFilePath($entity)
            );
        }

        return $dto;
    }
}