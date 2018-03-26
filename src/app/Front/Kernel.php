<?php

namespace Application\Front;

use Application\WebKernel;
use Core\View\View;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;

class Kernel extends WebKernel
{
    public function getPrefix(): string
    {
        return 'admin';
    }

    public function run(): void
    {
        $di = $this->getDI();

        // Error Handler
        $di->set(
            'dispatcher',
            function () use ($di) {
                // Get an EventsManager
                $eventsManager = $di->get('eventsManager');

                // Attach a listener
                $eventsManager->attach('dispatch', new Plugin\ErrorHandler());

                $dispatcher = new MvcDispatcher();

                // Bind the EventsManager to the dispatcher
                $dispatcher->setEventsManager($eventsManager);

                return $dispatcher;
            }
        );

        // Views config
        \define('VIEW_PATH', __DIR__  . '/Views/');

        /** @var View $view */
        $view = $di->get('view');
        $view->setViewsDir(VIEW_PATH);
        $view->setVar('STATIC_SERVER', STATIC_SERVER);
        $view->setMainView(VIEW_PATH . 'main');
        $view->setPartialsDir(VIEW_PATH . 'partials/');

        echo $this->handle()->getContent();
    }
}