<?php

namespace Application\Front;

use Application\Front\Plugin\DispatchEventPlugin;
use Application\WebKernel;
use Core\View\View;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Events\Manager as EventsManager;

class Kernel extends WebKernel
{
    public function getPrefix(): string
    {
        return 'front';
    }

    public function run(): void
    {
        $di = $this->getDI();
        $eventsManager = $di->get('eventsManager');

        // Attach a listener
        // $eventsManager->attach('dispatch', new Plugin\ErrorHandler());

        $eventsManager->attach('dispatch', new DispatchEventPlugin());

        $dispatcher = new Dispatcher();
        $dispatcher->setEventsManager($eventsManager);
        $di->set('dispatcher', $dispatcher);

        // Views config
        \define('VIEW_PATH', __DIR__  . '/Views/');

        /** @var View $view */
        $view = $di->get('view');
        $view->setViewsDir(VIEW_PATH);
        $view->setVar('STATIC_SERVER', STATIC_SERVER);
        $view->setMainView(VIEW_PATH . 'index');
        $view->setPartialsDir(VIEW_PATH . 'partials/');

        // Set locale
        setlocale(LC_ALL, 'en_EN');

        // Handle the request
        $response = $this->handle();
        $response->send();
    }
}
