<?php

namespace Eoko\Kes\Plugins\UniqueIdPlugin;

use Eoko\Kes\Plugins\AbstractPlugin;
use Eoko\Kes\Events\PreCreateOneEvent;

class UniqueIdPlugin extends AbstractPlugin
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

        if ($entity instanceof UniqueEntityInterface) {
            $entity->setId(uniqid());
        }
    }
}
