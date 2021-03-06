<?php

namespace Eoko\Kes\Plugins\SkuPlugin;

use Eoko\Kes\Plugins\AbstractPlugin;
use Ramsey\Uuid\Uuid;
use Eoko\Kes\Events\PreCreateOneEvent;

class SkuPlugin extends AbstractPlugin
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            PreCreateOneEvent::class => ['updateSku', -10],
        ];
    }

    public function updateSku(PreCreateOneEvent $event)
    {
        $entity = $event->getEntity();

        if ($entity instanceof SkuEntityInterface) {
            if (!$entity->getSku()) {
                $entity->setSku(Uuid::uuid4()->toString());
            }
        }
    }
}
