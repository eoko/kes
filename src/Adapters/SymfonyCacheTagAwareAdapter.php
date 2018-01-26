<?php


namespace Eoko\Kes\Adapters;

use Eoko\Kes\AdapterInterface;
use InvalidArgumentException;
use Psr\Cache\CacheItemInterface;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use Traversable;

class SymfonyCacheTagAwareAdapter implements AdapterInterface
{
    /** @var TagAwareAdapterInterface */
    protected $cache;

    /**
     * SymfonyCacheTagAwareAdapter constructor.
     *
     * @param $cache
     */
    public function __construct(TagAwareAdapterInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Invalidates cached items using tags.
     *
     * @param string[] $tags An array of tags to invalidate
     *
     * @return bool True on success
     *
     * @throws InvalidArgumentException When $tags is not valid
     */
    public function invalidateTags(array $tags): bool
    {
        return $this->cache->invalidateTags($tags);
    }

    /**
     * Returns a Cache Item representing the specified key.
     *
     * This method must always return a CacheItemInterface object, even in case of
     * a cache miss. It MUST NOT return null.
     *
     * @param string $key
     *                    The key for which to return the corresponding Cache Item
     *
     * @throws invalidArgumentException
     *                                  If the $key string is not a legal value a \Psr\Cache\InvalidArgumentException
     *                                  MUST be thrown
     *
     * @return cacheItemInterface
     *                            The corresponding Cache Item
     */
    public function getItem($key): CacheItemInterface
    {
        return $this->cache->getItem($key);
    }

    /**
     * Returns a traversable set of cache items.
     *
     * @param string[] $keys
     *                       An indexed array of keys of items to retrieve
     *
     * @throws invalidArgumentException
     *                                  If any of the keys in $keys are not a legal value a \Psr\Cache\InvalidArgumentException
     *                                  MUST be thrown
     *
     * @return Traversable
     *                     A traversable collection of Cache Items keyed by the cache keys of
     *                     each item. A Cache item will be returned for each key, even if that
     *                     key is not found. However, if no keys are specified then an empty
     *                     traversable MUST be returned instead.
     */
    public function getItems(array $keys = array()): Traversable
    {
        return $this->cache->getItems($keys);
    }

    /**
     * Confirms if the cache contains specified cache item.
     *
     * Note: This method MAY avoid retrieving the cached value for performance reasons.
     * This could result in a race condition with CacheItemInterface::get(). To avoid
     * such situation use CacheItemInterface::isHit() instead.
     *
     * @param string $key
     *                    The key for which to check existence
     *
     * @throws invalidArgumentException
     *                                  If the $key string is not a legal value a \Psr\Cache\InvalidArgumentException
     *                                  MUST be thrown
     *
     * @return bool
     *              True if item exists in the cache, false otherwise
     */
    public function hasItem($key): bool
    {
        return $this->cache->hasItem($key);
    }

    /**
     * Deletes all items in the pool.
     *
     * @return bool
     *              True if the pool was successfully cleared. False if there was an error.
     */
    public function clear(): bool
    {
        return $this->cache->clear();
    }

    /**
     * Removes the item from the pool.
     *
     * @param string $key
     *                    The key to delete
     *
     * @throws invalidArgumentException
     *                                  If the $key string is not a legal value a \Psr\Cache\InvalidArgumentException
     *                                  MUST be thrown
     *
     * @return bool
     *              True if the item was successfully removed. False if there was an error.
     */
    public function deleteItem($key): bool
    {
        return $this->cache->deleteItem($key);
    }

    /**
     * Removes multiple items from the pool.
     *
     * @param string[] $keys
     *                       An array of keys that should be removed from the pool
     *
     * @throws invalidArgumentException
     *                                  If any of the keys in $keys are not a legal value a \Psr\Cache\InvalidArgumentException
     *                                  MUST be thrown
     *
     * @return bool
     *              True if the items were successfully removed. False if there was an error.
     */
    public function deleteItems(array $keys): bool
    {
        return $this->cache->deleteItems($keys);
    }

    /**
     * Persists a cache item immediately.
     *
     * @param cacheItemInterface $item
     *                                 The cache item to save
     *
     * @return bool
     *              True if the item was successfully persisted. False if there was an error.
     */
    public function save(CacheItemInterface $item): bool
    {
        return $this->cache->save($item);
    }

    /**
     * Sets a cache item to be persisted later.
     *
     * @param cacheItemInterface $item
     *                                 The cache item to save
     *
     * @return bool
     *              False if the item could not be queued or if a commit was attempted and failed. True otherwise.
     */
    public function saveDeferred(CacheItemInterface $item): bool
    {
        return $this->cache->saveDeferred($item);
    }

    /**
     * Persists any deferred cache items.
     *
     * @return bool
     *              True if all not-yet-saved items were successfully saved or there were none. False otherwise.
     */
    public function commit(): bool
    {
        return $this->cache->commit();
    }
}
