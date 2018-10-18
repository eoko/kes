<?php

namespace Eoko\Kes;

use Exception;
use JMS\Serializer\SerializerInterface;

class EntityCacheService
{
    /** @var NamedCacheAdapter */
    protected $cache;

    /** @var SerializerInterface */
    protected $serializer;

    /** @var string */
    protected $fqcn;

    /** @var BaseEntityInterface */
    protected $ref;

    /**
     * EntityCacheService constructor.
     *
     * @param NamedCacheAdapter   $cache
     * @param SerializerInterface $serializer
     * @param BaseEntityInterface $entity
     */
    public function __construct(NamedCacheAdapter $cache, SerializerInterface $serializer, BaseEntityInterface $entity)
    {
        $this->cache = $cache;
        $this->serializer = $serializer;
        $this->fqcn = get_class($entity);
        $this->ref = $entity;
    }

    /**
     * @param BaseEntityInterface $entity
     *
     * @return BaseEntityInterface|BaseEntityInterface|object
     */
    public function getOneEntity(BaseEntityInterface $entity)
    {
        $data = $this->cache->getItem($entity->getId());
        $entity = $this->deserialize($data);

        return $entity;
    }

    /**
     * @param BaseEntityInterface $entity
     *
     * @return bool
     */
    public function deleteOneEntity(BaseEntityInterface $entity)
    {
        return $this->cache->deleteItem($entity->getId());
    }

    /**
     * @param BaseEntityInterface $entity
     *
     * @return BaseEntityInterface
     *
     * @throws Exception
     */
    public function addOneEntity(BaseEntityInterface $entity)
    {
        $id = $entity->getId();

        $data = $this->serialize($entity);

        if (!$this->cache->save($id, $data)) {
            throw new Exception('We cannot save your entity');
        }

        return $entity;
    }

    /**
     * @param BaseEntityInterface $entity
     *
     * @return BaseEntityInterface
     *
     * @throws Exception
     */
    public function updateOneEntity(BaseEntityInterface $entity)
    {
        $id = $entity->getId();
        $data = $this->serialize($entity);

        $this->cache->save($id, $data);

        if (!$this->cache->save($id, $data)) {
            throw new Exception('We cannot update your entity');
        }

        return $entity;
    }

    /**
     * @param BaseEntityInterface $entity
     *
     * @return string
     */
    protected function serialize(BaseEntityInterface $entity)
    {
        return $this
            ->serializer
            ->serialize($entity, 'json');
    }

    /**
     * @param $data
     *
     * @return object|BaseEntityInterface
     */
    protected function deserialize($data)
    {
        return $this
            ->serializer
            ->deserialize($data, $this->fqcn, 'json');
    }
}
