<?php

namespace Application;

use Core\MicroAbstract;
use Phalcon\Config;

abstract class MicroKernel extends MicroAbstract
{
    public function init(array $modules, array $config): void
    {
        $di = new \Phalcon\DI\FactoryDefault();

        // config
        $di->setShared('appConfig', function () use ($config) {
            return new Config($config);
        });

        // save modules list
        $di->setShared('modules', function () use ($modules) {
            return $modules;
        });

        // services loader
        $configServices = include APP_PATH . '/Services.php';

        $serviceLoader = new \Core\Service\LoaderService($configServices, $di);
        $di->set('serviceLoader', $serviceLoader, true);

        $this->setDI($di);
    }
}
