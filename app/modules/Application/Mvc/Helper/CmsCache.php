<?php
/**
 * @copyright Copyright (c) 2015 Oleksandr Torosh (http://yonastudio.com)
 * @author Oleksandr Torosh <webtorua@gmail.com>
 */
namespace Application\Mvc\Helper;

class CmsCache
{

    private static $instance = null;

    const DIR = '/cache/cms/';

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new CmsCache();
        }
        return self::$instance;
    }

    public function has($key)
    {
        if (is_file($this->file($key))) {
            return true;
        }
    }

    public function get($key)
    {
        $file = $this->file($key);
        if (is_file($file)) {
            return json_decode(file_get_contents($file), true);
        }
    }

    public function save($key, $data)
    {
        file_put_contents($this->file($key), json_encode($data));
    }

    private function file($key)
    {
        return APPLICATION_PATH . self::DIR . $key . '.json';
    }

}