<?php

namespace PeclPolyfill\Yac;

class Yac
{
    /**
     * @var string $_prefix
     */
    protected string $_prefix = '';

    /**
     * @var mixed
     */
    private array $cache = [];

    /**
     * @param string $prefix
     */
    public function __construct(string $prefix = '')
    {
    }

    /**
     * Add key-value pairs
     * 
     * @param string $keys
     * @param mixed $value
     * @param int $ttl
     * @return bool
     */
    public function add(string $keys, mixed $value, int $ttl = 0): bool
    {
        if (!array_key_exists($keys, $this->cache)) {
            $this->cache[$keys] = $value;
            return true;
        }

        return false;
    }

    /**
     * Set key value
     * 
     * @param array|string $keys Key name or key-value pair
     * @param mixed $value key value
     * @param int $ttl Validity period
     * 
     * @return bool
     */
    public function set(array|string $keys, mixed $value, int $ttl = 0): bool
    {
        if (array_key_exists($keys, $this->cache)) {
            $this->cache[$keys] = $value;
            return true;
        }

        return false;
    }

    /**
     * Magic method to set key value
     * 
     * @example $yac->goods = 'apple'; | Equivalent to $yac->set('goods', 'apple');
     * @param string $key
     * @param mixed $value
     * 
     * @return bool
     */
    public function __set(string $key, mixed $value)
    {
        $this->set($key, $value);
    }

    /**
     * Get the value of a key or the values of some keys
     * 
     * @param string|array $key Key name
     * @return mixed when successful => mixed | on failure => false
     * @example $yac->get('goods');
     * $yac->get(array('goods', 'test'));
     */
    public function get(string|array $key): mixed
    {
        if (array_key_exists($key, $this->cache)) {
            return $this->cache[$key];
        }

        return null;
    }

    /**
     * Magic method to get a key value
     * 
     * @example return $yac->goods; | Equivalent to $yac->get('goods')
     * @param string $keys
     * @return mixed when successful => mixed | on failure => false
     */
    public function __get(string $keys)
    {
        return $this->get($keys);
    }

    /**
     * Delete a key or keys
     * 
     * @param mixed $keys
     * @param int $ttl
     * @return bool
     * @example $yac->delete('goods');
     * $yac->delete(array('goods', 'test'));
     */
    public function delete(string|array $keys, int $ttl = 0): bool
    {
        if (array_key_exists($keys, $this->cache)) {
            unset($this->cache[$keys]);
            return true;
        }

        return false;
    }

    /**
     * Refresh the cache, that is, clear the cache
     * 
     * @return bool
     * @example
     */
    public function flush(): bool
    {
        if (!empty($this->cache)) {
            foreach ($this->cache as $key => $cache) {
                unset($this->cache[$key]);
            }

            return true;
        }

        return false;
    }

    /**
     * Get information such as cache usage
     * 
     * @return array
     * @example var_dump($yac->info());
     * array(11) {
     *     ["memory_size"]=> int(541065216)
     *     ["slots_memory_size"]=> int(4194304)
     *     ["values_memory_size"]=> int(536870912)
     *     ["segment_size"]=> int(4194304)
     *     ["segment_num"]=> int(128)
     *     ["miss"]=> int(0)
     *     ["hits"]=> int(955)
     *     ["fails"]=> int(0)
     *     ["kicks"]=> int(0)
     *     ["slots_size"]=> int(32768)
     *     ["slots_used"]=> int(955)
     * }
     */
    public function info(): array
    {
        return [];
    }

    /**
     * Export cache
     * 
     * @param int $num
     * @return mixed
     * @example
     */
    public function dump(int $num): mixed
    {
    }

    public function __destruct()
    {
        unset($this->shx);
    }
}
