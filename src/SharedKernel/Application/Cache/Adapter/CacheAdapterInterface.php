<?php

namespace App\SharedKernel\Application\Cache\Adapter;

interface CacheAdapterInterface
{
    public function get(string $key): mixed;
    public function getKeys(array $keys): mixed;
    public function set(string $key, mixed $value, int $ttl = null): void;
    public function delete(string $key): void;
}
