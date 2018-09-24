<?php

namespace Application\Web;

use Application\Web\Plugin\DispatchEventPlugin;
use Core\KernelAbstract;
use Core\Service\LoaderService;
use Core\Service\ModulesLoaderService;
use Core\Service\RoutesLoaderService;
use Core\View\View;
use Phalcon\Config;
use Phalcon\Di\FactoryDefault;
use Phalcon\DiInterface;
use Phalcon\Mvc\Dispatcher;

class WebKernel extends KernelAbstract
{
    public function getPrefix(): string
    {
        return 'web';
    }

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
        $configServices = include CONFIG_PATH . 'services.php';

        $serviceLoader = new LoaderService($configServices, $di);
        $di->set('serviceLoader', $serviceLoader, true);

        $this->setDI($di);
    }

    public function run(): void
    {
        $di = $this->getDI();

        $this->initEventsManager($di);

        $this->initDispatcher($di);

        $this->initView($di);

        $this->initLocale($di);

        // Handle the request
        $response = $this->handle();
        $response->send();
    }

    /**
     * @param DiInterface $di
     */
    private function initEventsManager(DiInterface $di)
    {
        $eventsManager = $di->get('eventsManager');
        $eventsManager->attach('dispatch', new DispatchEventPlugin());
    }

    /**
     * @param DiInterface $di
     */
    private function initDispatcher(DiInterface $di)
    {
        $eventsManager = $di->get('eventsManager');

        $dispatcher = new Dispatcher();
        $dispatcher->setEventsManager($eventsManager);
        $di->set('dispatcher', $dispatcher);
    }

    /**
     * @param DiInterface $di
     */
    private function initView(DiInterface $di)
    {
        // Views config
        \define('VIEW_PATH', APP_PATH . 'views/');

        /** @var View $view */
        $view = $di->get('view');
        $view->setViewsDir(VIEW_PATH);
        $view->setVar('STATIC_SERVER', STATIC_SERVER);
        $view->setMainView(VIEW_PATH . 'index');
        $view->setPartialsDir(VIEW_PATH . 'partials/');
    }

    /**
     * @param DiInterface $di
     */
    private function initLocale(DiInterface $di)
    {
        // Set locale
        setlocale(LC_ALL, 'en_EN');
    }
}
