<?php

namespace Application;

use Core\MicroAbstract;
use Core\Middleware\MicroModuleMiddleware;
use Core\Service\LoaderService;
use Core\MicroModules;
use Phalcon\Config;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\DI\FactoryDefault;

abstract class MicroKernel extends MicroAbstract
{
    public function init(array $listModules, array $config): void
    {
        $di = new FactoryDefault();

        // config
        $di->setShared('appConfig', function () use ($config) {
            return new Config($config);
        });

        // services loader
        $configServices = include APP_PATH . '/Services.php';
        $serviceLoader = new LoaderService($configServices, $di);
        $di->set('serviceLoader', $serviceLoader, true);

        // init modules service
        $modules = new MicroModules();
        $modules->init($this, $listModules);
        $di->setShared('modules', $modules);

        // attach middleware
        /** @var EventsManager $eventsManager */
        $eventsManager = $di->get('eventsManager');
        // initialize routes and modules
        $eventsManager->attach('micro', new MicroModuleMiddleware());

        // make events manager is in the DI container now
        $this->setEventsManager($eventsManager);

        $this->setDI($di);
    }
}
