<?php

namespace Application\Front\Middleware;

use Phalcon\Events\Event;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\MiddlewareInterface;

class NotFoundMiddleware implements MiddlewareInterface
{
    /**
     * The route has not been found
     *
     * @returns bool
     */
    public function beforeNotFound(Event $event, Micro $app): bool
    {
        $view = $app->getDI()->get('view');
        // view settings
        $view->setViewsDir(VIEW_PATH);
        // starts rendering process enabling the output buffering
        $view->start();

        // rendering
        $view->render(
            'errors',
            '404',
            [
                'message' => '404 - Not found',
            ]
        );

        // finishes the render process by stopping the output buffering
        $view->finish();

        // gets content
        $content = $view->getContent();

        $app->response->setStatusCode(404);
        $app->response->setContent($content);
        $app->response->send();

        return false;
    }

    /**
     * Calls the middleware
     *
     * @param Micro $application
     *
     * @return bool
     */
    public function call(Micro $application): bool
    {
        return true;
    }
}
