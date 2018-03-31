<?php

namespace User\Admin;

use Core\Interfaces\ModuleInterface;
use Phalcon\DiInterface;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\ModuleDefinitionInterface;

class Module implements ModuleDefinitionInterface, ModuleInterface
{

    public function requirements(): array
    {
        return [];
    }

    public function registerAutoloaders(DiInterface $di = null): void
    {
    }

    public function registerServices(DiInterface $di): void
    {
        /**
         * Setting up the default namespace
         * @var Dispatcher $dispatcher
         */
        $dispatcher = $di->get('dispatcher');
        $dispatcher->setDefaultNamespace('User\Admin\Controllers');
        $di->set('dispatcher', $dispatcher);

        /**
         * Setting up the view component
         * @var \Core\View\View $view
         */
        $view = $di->get('view');
        $view->setViewsDir(__DIR__ . '/Views/');
    }
}
