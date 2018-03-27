<?php

namespace CORE\Cache;

use Phalcon\Mvc\User\Component;

class Manager extends Component
{
    /**
     * Get cache data. If data not exists, generate data and save to cache
     *
     * @param string|int|array $key
     * @param callable         $closure
     * @param int              $lifetime
     *
     * @return mixed
     */
    public function load($key, $closure, $lifetime = 60)
    {
        if (is_array($key)) {
            $key = $this->key($key);
        }
        if ($lifetime == 0) {
            return call_user_func($closure);
        }
        $data = $this->cache->get($key, $lifetime);
        if (!$data) {
            $data = \call_user_func($closure);
            $this->cache->save($key, $data, $lifetime);
        }
        return $data;
    }

    public function save($key, $data, $lifetime)
    {
        if (APPLICATION_ENV !== 'development') {
            if (is_array($key)) {
                $key = $this->key($key);
            }
            $this->cache->save($key, $data, $lifetime);
        }
    }

    public function delete($key)
    {
        if (is_array($key)) {
            $key = $this->key($key);
        }
        $this->cache->delete($key);
    }

    /**Generate key from array
     * @param array $params
     * @return string
     */
    public function key(array $params = [])
    {
        return md5(serialize($params));
    }
}
