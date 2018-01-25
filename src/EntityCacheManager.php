<?php

namespace Eoko\Kes;

use Eoko\Kes\Plugins\PluginInterface;
use JMS\Serializer\SerializerInterface;
use Eoko\Kes\Events\PostCreateOneEvent;
use Eoko\Kes\Events\PostDeleteOneEvent;
use Eoko\Kes\Events\PostGetOneEvent;
use Eoko\Kes\Events\PostUpdateOneEvent;
use Eoko\Kes\Events\PreCreateOneEvent;
use Eoko\Kes\Events\PreDeleteOneEvent;
use Eoko\Kes\Events\PreGetOneEvent;
use Eoko\Kes\Events\PreUpdateOneEvent;
use Eoko\Kes\Exceptions\CacheDoesNotExist;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EntityCacheManager
{
    /** @var EntityCacheService[] */
    protected $caches = [];

    /** @var EventDispatcherInterface */
    protected $dispatcher;

    /** @var TagAwareAdapterInterface */
    protected $adapter;

    /** @var SerializerInterface */
    protected $serializer;

    /**
     * EntityCacheManager constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     * @param TagAwareAdapterInterface $adapter
     * @param SerializerInterface      $serializer
     */
    public function __construct(
        EventDispatcherInterface $dispatcher,
        TagAwareAdapterInterface $adapter,
        SerializerInterface $serializer
    ) {
        $this->dispatcher = $dispatcher;
        $this->adapter = $adapter;
        $this->serializer = $serializer;
    }

    /**
     * @param PluginInterface $plugin
     */
    public function registerPlugin(PluginInterface $plugin)
    {
        $this->dispatcher->addSubscriber($plugin);
    }

    /**
     * @param PluginInterface $plugin
     */
    public function unregisterPlugin(PluginInterface $plugin)
    {
        $this->dispatcher->removeSubscriber($plugin);
    }

    /**
     * @param BaseEntityInterface $entity
     */
    public function registerEntity(BaseEntityInterface $entity)
    {
        $cache = new NamedCacheAdapter($entity->internalName(), $this->adapter);

        $this->caches[$entity->internalName()] = new EntityCacheService(
            $cache,
            $this->serializer,
            $entity
        );
    }

    public function createOneEntity(BaseEntityInterface $entity, array $options = [])
    {
        $this
            ->dispatcher
            ->dispatch(
                PreCreateOneEvent::class,
                new PreCreateOneEvent($entity, $options)
            );

        $entity = $this->getCache($entity)->addOneEntity($entity);

        $this
            ->dispatcher
            ->dispatch(
                PostCreateOneEvent::class,
                new PostCreateOneEvent($entity, $options)
            );

        return $entity;
    }

    /**
     * @param BaseEntityInterface $entity
     * @param array               $options
     *
     * @return object|IdentifiableInterface
     */
    public function getOneEntity(BaseEntityInterface $entity, array $options = [])
    {
        $this
            ->dispatcher
            ->dispatch(
                PreGetOneEvent::class,
                new PreGetOneEvent($entity, $options)
            );

        $entity = $this->getCache($entity)->getOneEntity($entity);

        $this
            ->dispatcher
            ->dispatch(
                PostGetOneEvent::class,
                new PostGetOneEvent($entity, $options)
            );

        return $entity;
    }

    /**
     * @param BaseEntityInterface $entity
     * @param array               $options
     *
     * @return object|IdentifiableInterface
     */
    public function addOneEntity(BaseEntityInterface $entity, array $options = [])
    {
        $this
            ->dispatcher
            ->dispatch(
                PreGetOneEvent::class,
                new PreGetOneEvent($entity, $options)
            );

        $entity = $this->getCache($entity)->addOneEntity($entity);

        $this
            ->dispatcher
            ->dispatch(
                PostGetOneEvent::class,
                new PostGetOneEvent($entity, $options)
            );

        return $entity;
    }

    /**
     * @param BaseEntityInterface $entity
     * @param array               $options
     *
     * @return BaseEntityInterface
     */
    public function updateOneEntity(BaseEntityInterface $entity, array $options = [])
    {
        $this
            ->dispatcher
            ->dispatch(
                PreUpdateOneEvent::class,
                new PreUpdateOneEvent($entity, $options)
            );

        $entity = $this->getCache($entity)->updateOneEntity($entity);

        $this
            ->dispatcher
            ->dispatch(
                PostUpdateOneEvent::class,
                new PostUpdateOneEvent($entity, $options)
            );

        return $entity;
    }

    /**
     * @param BaseEntityInterface $entity
     * @param array               $options
     *
     * @return bool
     */
    public function deleteOneEntity(BaseEntityInterface $entity, array $options = [])
    {
        $this
            ->dispatcher
            ->dispatch(
                PreDeleteOneEvent::class,
                new PreDeleteOneEvent($entity, $options)
            );

        $this->getCache($entity)->deleteOneEntity($entity);

        $this
            ->dispatcher
            ->dispatch(
                PostDeleteOneEvent::class,
                new PostDeleteOneEvent($entity, $options)
            );

        return true;
    }

    /**
     * @param BaseEntityInterface $entity
     *
     * @return EntityCacheService
     *
     * @throws CacheDoesNotExist
     */
    public function getCache(BaseEntityInterface $entity)
    {
        if (!isset($this->caches[$entity->internalName()])) {
            throw new CacheDoesNotExist();
        }

        return $this->caches[$entity->internalName()];
    }
}
