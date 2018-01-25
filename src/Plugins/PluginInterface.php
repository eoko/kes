<?php

namespace Eoko\Kes\Plugins;

use Eoko\Kes\EntityCacheManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

interface PluginInterface extends EventSubscriberInterface
{
    /**
     * @param EntityCacheManager $ecm
     */
    public function setEntityCacheManager(EntityCacheManager $ecm): void;

    /**
     * @return EntityCacheManager
     */
    public function getEntityCacheManager(): EntityCacheManager;
}
