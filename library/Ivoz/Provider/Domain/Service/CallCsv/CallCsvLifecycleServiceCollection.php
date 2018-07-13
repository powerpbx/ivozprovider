<?php

namespace Ivoz\Provider\Domain\Service\CallCsv;

use Ivoz\Core\Domain\Service\LifecycleServiceCollectionInterface;
use Ivoz\Core\Domain\Service\LifecycleServiceCollectionTrait;

/**
 * @codeCoverageIgnore
 */
class CallCsvLifecycleServiceCollection implements LifecycleServiceCollectionInterface
{
    use LifecycleServiceCollectionTrait;

    protected function addService(CallCsvLifecycleEventHandlerInterface $service)
    {
        $this->services[] = $service;
    }
}