<?php

namespace Eoko\Kes\Plugins\MetadataPlugin;

use DateTime;
use Eoko\Kes\Events\EventInterface;
use Eoko\Kes\Events\PreCreateOneEvent;
use Eoko\Kes\Events\PreUpdateOneEvent;
use Eoko\Kes\Plugins\AbstractPlugin;

class MetadataPlugin extends AbstractPlugin
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            PreCreateOneEvent::class => ['preCreate', -10],
            PreUpdateOneEvent::class => ['preUpdate', -10],
        ];
    }

    /**
     * @param EventInterface $event
     */
    public function preCreate(EventInterface $event)
    {
        $entity = $event->getEntity();

        if ($entity instanceof MetadataEntityInterface) {
            $entity->setCreated(new DateTime());
            $entity->setUpdated(new DateTime());
        }
    }

    /**
     * @param EventInterface $event
     */
    public function preUpdate(EventInterface $event)
    {
        $entity = $event->getEntity();

        if ($entity instanceof MetadataEntityInterface) {
            $entity->setUpdated(new DateTime());
        }
    }
}
