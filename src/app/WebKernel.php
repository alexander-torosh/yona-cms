<?php

namespace Application;

use Core\KernelAbstract;
use Core\Service\ModulesLoaderService;
use Core\Service\RoutesLoaderService;
use Phalcon\Config;

abstract class WebKernel extends KernelAbstract
{
    public function init(array $modules, array $config)
    {
        $di = new \Phalcon\DI\FactoryDefault();
        $di->setShared('appConfig', function () use ($config) {
            return new Config($config);
        });

        $modulesService = new ModulesLoaderService();
        $modulesService->register($this, $modules);

        // Service loader
        $configServices = include APP_PATH . '/Services.php';

        $serviceLoader = new \Core\Service\LoaderService($configServices, $di);
        $di->set('serviceLoader', $serviceLoader, true);

        // Include routers
        $routesService = new RoutesLoaderService();
        $routesService->include($this);

        $this->setDI($di);
    }
}
