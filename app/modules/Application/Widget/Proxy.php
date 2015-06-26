<?php

/**
 * Proxy
 * @copyright Copyright (c) 2011 - 2012 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Application\Widget;

class Proxy extends \Phalcon\Mvc\User\Component
{

    const NULLCACHE = 'NULLCACHE';

    public static $cache = null; // injected
    private $cacheEnabled = true;
    private $cacheTime = 60;
    private $object;
    private $namespace;
    private $hide_for_mobile = false;

    public function __construct($namespace = 'Index', array $params = array())
    {
        $this->namespace = $namespace;

        ucfirst($namespace);
        $class = $namespace . '\\Widget\\' . $namespace . 'Widget';
        $this->object = new $class();
        $this->object->setModule($namespace);

        $registry = $this->getDI()->get('registry');
        $this->cacheEnabled = $registry->cms['WIDGETS_CACHE'];

        if (isset($params['cache']) && !$params['cache']) {
            $this->cacheEnabled = false;
        }
        if (isset($params['time']) && $params['time']) {
            $this->cacheTime = (int) $params['time'];
        }
        if (isset($params['hide_for_mobile']) && $params['hide_for_mobile']) {
            if (MOBILE_DEVICE) {
                $this->hide_for_mobile = true;
            }
        }

    }

    public function __call($method, array $params)
    {
        if ($this->hide_for_mobile) {
            return;
        }
        try {
            if ($this->cacheEnabled) {
                $paramsString = md5(serialize($params));
                $cacheKey = md5($this->namespace . '::' . $method . $paramsString . LANG . (string) MOBILE_DEVICE);
                $results = self::$cache->get($cacheKey);
                if (!$results) {
                    if (method_exists($this->object, $method)) {
                        $results = $this->getResults($method, $params);
                        if (!$results) {
                            $results = self::NULLCACHE;
                        }
                        $cacheTime = $this->cacheTime + rand(0, 60);
                        self::$cache->save($cacheKey, $results, $cacheTime);
                        if ($results !== self::NULLCACHE) {
                            return $results;
                        }
                    } else {
                        echo $this->namespace . 'Widget::' . $method . ' not exists';
                    }
                } else {
                    if ($results == self::NULLCACHE) {
                        return;
                    } else {
                        return $results;
                    }
                }
            } else {
                return $this->getResults($method, $params);
            }
        } catch (\Exception $e) {
            $this->cacheEnabled = false;
            echo '<!--' . htmlspecialchars('Error. ' . $this->namespace . 'Widget::' . $method . '. ' . $e->getMessage()) . '-->';
        }

    }

    private function getResults($method, $params)
    {
        ob_start();
        call_user_func_array(array($this->object, $method), $params);
        $results = ob_get_contents();
        ob_end_clean();
        return $results;

    }

}
