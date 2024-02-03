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
     * 添加键值对
     * @param string $keys 键名
     * @param mixed $value 键值
     * @param int $ttl 有效期
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
     * 设置键值
     * @param array|string $keys 键名或键值对
     * @param mixed $value 键值
     * @param int $ttl 有效期
     * @return bool
     * @example
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
     * 设置键值的魔术方法
     * @example $yac->goods = 'apple';//相当于$yac->set('goods', 'apple');
     * @param string $key 键
     * @param mixed $value 值
     * @return bool
     */
    public function __set(string $key, mixed $value)
    {
        $this->set($key, $value);
    }

    /**
     * 获取某个键的值或某些键的值
     * @param string|array $key 键名
     * @return mixed 成功时mixed，失败时false
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
     * 获取某个键值的魔术方法
     * @example return $yac->goods;//相当于$yac->get('goods')
     * @param string $keys 键名
     * @return mixed 成功时mixed，失败时false
     */
    public function __get(string $keys)
    {
        return $this->get($keys);
    }

    /**
     * 删除某个键或某几个键
     * @param mixed $keys 要删除的键
     * @param int $ttl 延迟删除时间
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
     * 刷新缓存，即清空缓存
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
     * 获取缓存使用情况等信息
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
    }

    /**
     * 导出缓存
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
