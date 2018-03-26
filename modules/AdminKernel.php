<?php

namespace Modules;

use Core\KernelAbstract;
use Core\Service\ModulesLoaderService;
use Core\Service\RoutesLoaderService;

class AdminKernel extends KernelAbstract
{
    public function getPrefix(): string
    {
        return 'admin';
    }

    public function init(array $modules, array $config)
    {
        $di = new \Phalcon\DI\FactoryDefault();
        $di->setShared('appConfig', function() use ($config) {
            return $config;
        });

        $modulesService = new ModulesLoaderService();
        $modulesService->registerNamespaces($this, $modules);
        $modulesService->register($this, $modules);

        $this->setDI($di);
    }

    public function run(): void
    {
        $di = $this->getDI();

        // Service loader
        $configServices = include APP_PATH . '/services.php';

        $serviceLoader = new \Core\Service\LoaderService($configServices, $di);
        $di->set('serviceLoader', $serviceLoader, true);

        \define('VIEW_PATH', APP_PATH  . '/views/'. $this->getPrefix() . '/');

        // Views config
        $view = $di->get('view');
        $view->setViewsDir(VIEW_PATH);
        $view->setVar('IMAGES_SERVER', IMAGES_SERVER);
        $view->setVar('STATIC_SERVER', STATIC_SERVER);
        $view->setMainView(VIEW_PATH . 'main');
        $view->setPartialsDir(VIEW_PATH . '/partials/');

        // Include routers
        $routesService = new RoutesLoaderService();
        $routesService->include($this);

        $this->setDI($di);

        echo $this->handle()->getContent();

    }
}