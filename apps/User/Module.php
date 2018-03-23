<?php

namespace Order;

use Core\View;
use Phalcon\DiInterface;
use Phalcon\Loader;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\ModuleDefinitionInterface;

class Module implements ModuleDefinitionInterface
{

    public function registerAutoloaders(DiInterface $di = null): void
    {
        $loader = new Loader();

        $loader->registerNamespaces(
            [
                'User\Controllers' => __DIR__ . '/Controllers/',
                'User\Models'      => __DIR__ . '/Models/',
            ]
        );

        $loader->register();
    }

    public function registerServices(DiInterface $di): void
    {
        /**
         * Setting up the default namespace
         * @var Dispatcher $dispatcher
         */
        $dispatcher = $di->get('dispatcher');
        $dispatcher->setDefaultNamespace('User\Controllers');
        $di->set('dispatcher', $dispatcher);

        /**
         * Setting up the view component
         * @var View $view
         */
        $view = $di->get('view');
        $view->setViewsDir(__DIR__ . '/Views/');
    }
}