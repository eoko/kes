<?php

namespace Eoko\Kes;

use Eoko\Kes\Adapters\SymfonyCacheTagAwareAdapter;
use Eoko\Kes\Plugins\PluginInterface;
use JMS\Serializer\SerializerBuilder;
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
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
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
     * @param AdapterInterface         $adapter
     * @param SerializerInterface      $serializer
     */
    public function __construct(
        AdapterInterface $adapter = null,
        EventDispatcherInterface $dispatcher = null,
        SerializerInterface $serializer = null
    ) {
        $this->adapter = $adapter ?? new SymfonyCacheTagAwareAdapter(new TagAwareAdapter(new ArrayAdapter()));
        $this->dispatcher = $dispatcher ?? new EventDispatcher();
        $this->serializer = $serializer ?? SerializerBuilder::create()->build();
    }

    /**
     * @param PluginInterface $plugin
     */
    public function registerPlugin(PluginInterface $plugin)
    {
        $plugin->setEntityCacheManager($this);
        $this->dispatcher->addSubscriber($plugin);
    }

    /**
     * @param PluginInterface $plugin
     */
    public function unregisterPlugin(PluginInterface $plugin)
    {
        $this->dispatcher->removeSubscriber($plugin);
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
        $name = $entity instanceof NameableInterface ? $entity->name() : self::slugifyFQCN(get_class($entity));

        if (!isset($this->caches[$name])) {
            $cache = new NamedCacheAdapter($name, $this->adapter);

            $this->caches[$name] = new EntityCacheService(
                $cache,
                $this->serializer,
                $entity
            );
        }

        return $this->caches[$name];
    }

    /**
     * @param string $fqcn
     *
     * @return string
     */
    protected function slugifyFQCN(string $fqcn): string
    {
        return strtolower(str_replace('\\', '-', $fqcn));
    }
}
