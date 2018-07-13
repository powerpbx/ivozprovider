<?php

namespace Ivoz\Provider\Domain\Service\CallCsv;

use Ivoz\Core\Domain\Service\LifecycleEventHandlerInterface;
use Ivoz\Provider\Domain\Model\CallCsv\CallCsvInterface;

interface CallCsvLifecycleEventHandlerInterface extends LifecycleEventHandlerInterface
{
    public function execute(CallCsvInterface $entity);
}