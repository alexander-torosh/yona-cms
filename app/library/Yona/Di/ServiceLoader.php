<?php
/**
 * @author Oleksandr Torosh <webtorua@gmail.com>
 */

namespace Yona\Di;

use Phalcon\Config;
use Phalcon\Di\FactoryDefault;
use Phalcon\Di\Injectable;
use Phalcon\DiInterface;
use Phalcon\Exception;

class ServiceLoader extends Injectable
{
    /**
     * ServiceLoader constructor.
     * @param $config
     * @param DiInterface|null $di
     * @param array $autoLoad
     * @param array $exclude
     */
    public function __construct($config, DiInterface $di = null, array $autoLoad = [], array $exclude = [])
    {
        $this->setDI($di);
        $services = $this->buildConfig($config, $exclude);
        $this->setServices($services, $autoLoad);
    }

    /**
     * @param $config
     * @param array $exclude
     * @return Config
     */
    public function buildConfig($config, array $exclude = [])
    {
        $configResult = [];
        foreach ($config as $index => $el) {
            if (in_array($index, $exclude)) {
                continue;
            }
            if (is_array($el)) {
                if (array_key_exists('adapters', $el) && array_key_exists('default', $el)) {
                    $configResult[$index] = $el['adapters'][$el['default']];
                } else {
                    $configResult[$index] = $el;
                }
            } else {
                $configResult[$index] = $el;
            }
        }
        return new Config($configResult);
    }

    /**
     * @param $services
     * @param array $autoLoad
     */
    public function setServices($services, array $autoLoad = [])
    {
        $di = $this->getDI();
        $shared = false;
        foreach ($services as $name => $params) {
            if (!is_string($params) && is_callable($params, true)) {
                $shared = true;
                $params = function () use ($params, $di) {
                    return $params($di);
                };
            } else {
                if ($params instanceof Config || is_array($params)) {
                    $shared = !(isset($params['shared']) && !$params['shared']);
                }
                if ($params instanceof Config && !empty($params['className'])) {
                    $params = $params->toArray();
                }
            }
            $di->set($name, $params, $shared);
        }

        if (count($autoLoad)) {
            $this->load($autoLoad);
        }

        $di->set('config', $services);
    }

    public function load(array $names)
    {
        $di = $this->getDI();
        foreach ($names as $name) {
            if ($di->has($name)) {
                $di->get($name);
            } else {
                throw new Exception("Service $name not found in config file");
            }
        }
    }
}