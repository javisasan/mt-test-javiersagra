<?php

namespace App\SharedKernel\Infrastructure\Cache\Adapter;

use App\SharedKernel\Application\Cache\Adapter\CacheAdapterInterface;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class RedisCacheAdapter implements CacheAdapterInterface
{
    public const TTL = 120;

    private RedisAdapter $adapter;

    public function __construct(CacheInterface $cache)
    {
        $this->adapter = $cache;
    }

    public function get(string $key): mixed
    {
        $data = $this->adapter->getItem($key);

        if (!$data->isHit()) {
            return false;
        }

        return $data->get();
    }

    public function getKeys(array $keys): mixed
    {
        $result = [];

        $datas = $this->adapter->getItems($keys);

        foreach ($datas as $data) {
            $result[$data->getKey()] = $data->isHit() ? $data->get() : false;
        }

        return $result;
    }

    public function set(string $key, mixed $value, int $ttl = null): void
    {
        if ($ttl === null) {
            $ttl = self::TTL;
        }

        $this->adapter->delete($key);
        $this->adapter->get($key, function (ItemInterface $item) use ($value, $ttl) {
            $item->expiresAfter($ttl);

            return $value;
        });
    }

    public function delete(string $key): void
    {
        $this->adapter->delete($key);
    }
}
