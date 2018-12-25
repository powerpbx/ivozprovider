<?php

namespace Ivoz\Provider\Infrastructure\Domain\Service\TransformationRule;

use Ivoz\Core\Infrastructure\Domain\Service\XmlRpc\XmlRpcTrunksRequestInterface;
use Ivoz\Provider\Domain\Model\TransformationRule\TransformationRuleInterface;
use Ivoz\Provider\Domain\Service\TransformationRule\TransformationRuleLifecycleEventHandlerInterface;

class SendTrunksDialplanReloadRequest implements TransformationRuleLifecycleEventHandlerInterface
{
    protected $trunksDialplanReload;

    public function __construct(
        XmlRpcTrunksRequestInterface $trunksDialplanReload
    ) {
        $this->trunksDialplanReload = $trunksDialplanReload;
    }

    public static function getSubscribedEvents()
    {
        return [
            self::EVENT_ON_COMMIT => 20
        ];
    }

    public function execute(TransformationRuleInterface $entity)
    {
        $this->trunksDialplanReload->send();
    }
}
