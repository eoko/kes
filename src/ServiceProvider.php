<?php

namespace Eoko\Kes;

use Doctrine\Common\Annotations\AnnotationRegistry;
use JMS\Serializer\SerializerInterface;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $app)
    {
        AnnotationRegistry::registerLoader('class_exists');
        /*
         * @param Container $c
         * @return EntityCacheManager
         */
        $app[EntityCacheManager::class] = function (Container $c) {
            /** @var EventDispatcherInterface $dispatcher */
            $dispatcher = $c['cache.dispatcher'];

            /** @var SerializerInterface $serializer */
            $serializer = $c['cache.serializer'];

            /** @var TagAwareAdapterInterface $adapter */
            $adapter = $c['cache.adapter'];

            return new EntityCacheManager($dispatcher, $adapter, $serializer);
        };

        return $app;
    }
}
