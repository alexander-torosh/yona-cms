<?php

namespace Dashboard;

use Dashboard\Application\Plugin\ErrorHandler;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;
use Phalcon\Translate\Adapter\NativeArray;

class Kernel extends \Phalcon\Mvc\Application
{
    public function run()
    {
        // Service loader
        $configServices = include BASE_PATH . '/config/services.php';
        $di = new \Phalcon\DI\FactoryDefault();
        $serviceLoader = new \Core\Service\LoaderService($configServices, $di);
        $di->set('serviceLoader', $serviceLoader, true);


        // Error Handler
        $di->set(
            'dispatcher',
            function () use ($di) {
                // Get an EventsManager
                $eventsManager = $di->get('eventsManager');

                // Attach a listener
                $eventsManager->attach('dispatch', new ErrorHandler());

                $dispatcher = new MvcDispatcher();

                // Bind the EventsManager to the dispatcher
                $dispatcher->setEventsManager($eventsManager);

                return $dispatcher;
            }
        );

        $appConfig = $di->get('appConfig');

        \define('VIEW_PATH', APPLICATION_PATH . '/views/');
        \define('IMAGES_SERVER', $appConfig->get('images_server'));
        \define('STATIC_SERVER', $appConfig->get('static_server'));

        // Initialize the Message component
        $di->setShared('messages', function () {
            $messages = require './messages/en.php';

            // Return a translation object
            return new NativeArray(
                [
                    'content' => $messages,
                ]
            );
        });

        // add a filter to skip encoding for quotes
        $di->setShared('filter', function () {

            $filter = new \Phalcon\Filter();

            $filter->add('estring', function ($value) {
                return filter_var(
                    $value,
                    FILTER_SANITIZE_STRING,
                    [
                        'flags' => FILTER_FLAG_NO_ENCODE_QUOTES,
                    ]
                );
            });

            return $filter;

        });

        // Views config
        $view = $di->get('view');
        $view->setViewsDir(VIEW_PATH);
        $view->setVar('IMAGES_SERVER', IMAGES_SERVER);
        $view->setVar('STATIC_SERVER', STATIC_SERVER);
        $view->setMainView(VIEW_PATH . 'main');
        $view->setPartialsDir(VIEW_PATH . '/partials/');


        //Register the installed modules
        $this->registerModules([
            'index'           => [
                'className' => \Dashboard\Index\Module::class,
                'path'      => __DIR__ . '/modules/Index/Module.php',
            ],
        ]);

        // Include routers
        $di->set('router',new Router());

        $this->setDI($di);

        echo $this->handle()->getContent();

    }
}