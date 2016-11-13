<?php
/**
 * @author Oleksandr Torosh <webtorua@gmail.com>
 */

namespace Application\Cache;

use Phalcon\Mvc\User\Component;

class Manager extends Component
{
    /**
     * Get cache data. If data not exists, generate data and save to cache
     */
    public function load($key, $closure, $lifetime = 60)
    {
        if (is_array($key)) {
            $key = $this->key($key);
        }
        if ($lifetime == 0) {
            return $closure();
        }
        $data = $this->cache->get($key, $lifetime);
        if (!$data) {
            $data = $closure();
            $this->cache->save($key, $data, $lifetime);
        }
        return $data;
    }

    public function save($key, $data, $lifetime)
    {
        if (is_array($key)) {
            $key = $this->key($key);
        }
        $this->cache->save($key, $data, $lifetime);
    }

    public function delete($key)
    {
        if (is_array($key)) {
            $key = $this->key($key);
        }
        $this->cache->delete($key);
    }

    public function key(array $params = [])
    {
        return md5(serialize($params));
    }

}