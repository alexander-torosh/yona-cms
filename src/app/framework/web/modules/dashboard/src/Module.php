<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Dashboard;

use Phalcon\Di\DiInterface;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Tag;

class Module implements ModuleDefinitionInterface
{
    public function registerAutoloaders(DiInterface $container = null)
    {
    }

    public function registerServices(DiInterface $container)
    {
        // Registering a dispatcher
        $dispatcher = new Dispatcher();
        $dispatcher->setEventsManager($container->get('eventsManager'));
        $dispatcher->setDefaultNamespace('Dashboard\Controllers');
        $container->setShared('dispatcher', $dispatcher);

        // Registering the view component
        $view = $container->get('view');
        $view
            ->setViewsDir(__DIR__.'/../views/')
            ->setMainView('dashboard')
        ;

        Tag::setTitle('Yona CMS Dashboard');
    }
}
