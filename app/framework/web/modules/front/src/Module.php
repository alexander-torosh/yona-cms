<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Front;

use Phalcon\Di\DiInterface;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Web\WebView;

class Module implements ModuleDefinitionInterface
{
    public function registerAutoloaders(DiInterface $container = null)
    {

    }

    public function registerServices(DiInterface $container)
    {
        // Registering a dispatcher
        $container->set(
            'dispatcher',
            function () use ($container) {
                $dispatcher = new Dispatcher();
                $dispatcher->setEventsManager($container->get('eventsManager'));
                $dispatcher->setDefaultNamespace('Front\Controllers');

                return $dispatcher;
            }
        );

        // Registering the view component
        $container->set(
            'view',
            function () {
                $view = new WebView();
                $view
                    ->setViewsDir(__DIR__ . '/../views/')
                    ->setMainView('front');

                return $view;
            }
        );
    }
}