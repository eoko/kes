<?php

namespace Eoko\Kes\Plugins\DisabledPlugin;

use Eoko\Kes\Events\EventInterface;
use Eoko\Kes\Events\PostGetOneEvent;
use Eoko\Kes\Events\PreDeleteOneEvent;
use Eoko\Kes\Exceptions\NotFound;
use Eoko\Kes\Plugins\AbstractPlugin;

class DisabledPlugin extends AbstractPlugin
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            PostGetOneEvent::class => ['checkIsDisabled', -10],
            PreDeleteOneEvent::class => ['unDeletable', -10],
        ];
    }

    /**
     * @param EventInterface $event
     *
     * @throws UndeletableEntityException
     */
    public function unDeletable(EventInterface $event)
    {
        if ($event->getEntity() instanceof DisableEntityInterface) {
            throw new UndeletableEntityException($event->getEntity());
        }
    }

    /**
     * @param EventInterface $event
     */
    public function checkEntityExist(EventInterface $event)
    {
        $this->getEntityCacheManager()->getOneEntity($event->getEntity());
    }

    /**
     * @param EventInterface $event
     *
     * @throws NotFound
     */
    public function checkIsDisabled(EventInterface $event)
    {
        $entity = $event->getEntity();
        $ignore = $event->getOptions()['ignoreDisabled'] ?? false;

        if (!$ignore
            && $entity instanceof DisableEntityInterface
            && $entity->isDisabled()
        ) {
            throw new NotFound();
        }
    }
}
