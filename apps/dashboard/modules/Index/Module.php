<?php

namespace Dashboard\Index;

use Core\View\View;
use Phalcon\DiInterface;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\ModuleDefinitionInterface;

class Module implements ModuleDefinitionInterface
{

    public function registerAutoloaders(DiInterface $di = null)
    {

    }

    public function registerServices(DiInterface $di)
    {
        /**
         * Setting up the default namespace
         * @var Dispatcher $dispatcher
         */
        $dispatcher = $di->get('dispatcher');
        $dispatcher->setDefaultNamespace('Dashboard\\Index\\Controllers');
        $di->set('dispatcher', $dispatcher);

        /**
         * Setting up the view component
         * @var View $view
         */
        $view = $di->get('view');
        $view->setViewsDir(__DIR__ . '/Views/');

    }
}