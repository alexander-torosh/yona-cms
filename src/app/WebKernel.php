<?php

namespace Application;

use Core\KernelAbstract;
use Core\Service\ModulesLoaderService;
use Core\Service\RoutesLoaderService;
use Core\Service\LoaderService;
use Phalcon\Config;
use Phalcon\DI\FactoryDefault;

abstract class WebKernel extends KernelAbstract
{
    public function init(array $modules, array $config): void
    {
        $di = new FactoryDefault();
        $di->setShared('appConfig', function () use ($config) {
            return new Config($config);
        });

        // Register Modules
        $modulesService = new ModulesLoaderService();
        $modulesService->register($this, $modules);

        // Include Routes
        $routesService = new RoutesLoaderService();
        $routesService->include($this);

        // Service loader
        $configServices = include APP_PATH . '/Services.php';

        $serviceLoader = new LoaderService($configServices, $di);
        $di->set('serviceLoader', $serviceLoader, true);

        $this->setDI($di);
    }
}
