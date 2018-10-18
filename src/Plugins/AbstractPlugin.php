<?php

namespace Eoko\Kes\Plugins;

use Eoko\Kes\EntityCacheManager;

abstract class AbstractPlugin implements PluginInterface
{
    /** @var EntityCacheManager */
    protected $ecm;

    /**
     * {@inheritdoc}
     */
    public function setEntityCacheManager(EntityCacheManager $ecm): void
    {
        $this->ecm = $ecm;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityCacheManager(): EntityCacheManager
    {
        return $this->ecm;
    }
}
