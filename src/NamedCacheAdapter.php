<?php

namespace Eoko\Kes;

use Psr\Cache\CacheItemInterface;
use Eoko\Kes\Exceptions\ItemNotFound;

/**
 * Class NamedCache.
 */
class NamedCacheAdapter
{
    /** @var string */
    protected $prefix;

    /** @var string */
    protected $name;

    /** @var AdapterInterface */
    protected $adapter;

    /**
     * NamedCache constructor.
     *
     * @param string           $name
     * @param AdapterInterface $adapter
     */
    public function __construct(string $name = '', AdapterInterface $adapter)
    {
        $this->name = $name;
        $this->prefix = $name.'_';
        $this->adapter = $adapter;
    }

    /**
     * @param $id
     *
     * @return mixed
     *
     * @throws ItemNotFound
     */
    public function getItem(string $id)
    {
        $key = $this->buildKey($id);
        $item = $this->adapter->getItem($key);

        if (is_null($item) || !$item->isHit()) {
            throw new ItemNotFound('We cannot find item with key `'.$key.'`.');
        }

        return $item->get();
    }

    /**
     * @param array $keys
     *
     * @return array|\Generator|\Traversable
     */
    public function getItems(array $keys = array())
    {
        $keys = array_map([$this, 'buildKey'], $keys);

        /** @var CacheItemInterface $item */
        foreach ($this->adapter->getItems($keys) as $item) {
            if (!is_null($item) && $item->isHit()) {
                yield $item->get();
            }
        }
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function exist($id)
    {
        return $this->adapter->hasItem($this->buildKey($id));
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function deleteItem($id)
    {
        return $this->adapter->deleteItems([$this->buildKey($id)]);
    }

    /**
     * @param array $keys
     *
     * @return bool
     */
    public function deleteItems(array $keys)
    {
        return $this->adapter->deleteItems(array_map([$this, 'buildKey'], $keys));
    }

    /**
     * @return bool|mixed
     */
    public function invalidate()
    {
        return $this->adapter->invalidateTags([$this->name]);
    }

    /**
     * @param $id
     * @param $content
     *
     * @return bool
     */
    public function save($id, $content): bool
    {
        $key = $this->buildKey($id);
        $item = $this->adapter->getItem($key);

        $item->set($content);
        $item->tag([$this->getName()]);

        return $this->adapter->save($item);
    }

    /**
     * @param $id
     * @param $content
     *
     * @return bool
     */
    public function saveDeferred($id, $content): bool
    {
        $key = $this->buildKey($id);
        $item = $this->adapter->getItem($key);

        $item->set($content);
        $item->tag([$this->getName()]);

        return $this->adapter->saveDeferred($item);
    }

    /**
     * @return bool
     */
    public function commit(): bool
    {
        return $this->adapter->commit();
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param $id
     *
     * @return string
     */
    protected function buildKey($id)
    {
        return $this->getPrefix().$id;
    }
}
