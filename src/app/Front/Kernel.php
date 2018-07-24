<?php

namespace Application\Front;

use Application\Front\Plugin\ErrorHandler;
use Application\MicroKernel;
use Application\Front\Middleware\NotFoundMiddleware;
use Core\View\View;
use Phalcon\Events\Manager as EventsManager;

class Kernel extends MicroKernel
{
    public function getPrefix(): string
    {
        return 'front';
    }

    public function run(): void
    {
        $di = $this->getDI();

        // Views config
        \define('VIEW_PATH', __DIR__  . '/Views/');

        /** @var View $view */
        $view = $di->get('view');
        $view->setViewsDir(VIEW_PATH);
        $view->setVar('STATIC_SERVER', STATIC_SERVER);
        $view->setMainView(VIEW_PATH . 'main');
        $view->setPartialsDir(VIEW_PATH . 'partials/');

        // set locale
        setlocale(LC_ALL, 'en_EN');

        // attach middleware
        /** @var EventsManager $eventsManager */
        $eventsManager = $this->getEventsManager();

        // when not found route
        $eventsManager->attach('micro', new NotFoundMiddleware());

        // make events manager is in the DI container now
        $this->setEventsManager($eventsManager);

        // middleware after the route is executed
        $this->after(
            function () {
                $response = $this->getReturnedValue();

                if ($response !== null) {
                    if (is_string($response)) {
                        $this->response->setContent($response);
                    } else {
                        $this->response->setJsonContent($response);
                    }

                    $this->response->setStatusCode(200);
                    $this->response->send();
                }
            }
        );

        // error handler
        $this->error(
            function (\Throwable $e) {
                $handler = new ErrorHandler();
                return $handler->handle($this, $e);
            }
        );

        $this->handle();
    }
}
