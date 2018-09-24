<?php
/**
 * @author Oleksandr Torosh <webtorua@gmail.com>
 */

namespace Application\Web\Plugin;

use Exception;
use Phalcon\Events\Event;
use Phalcon\Http\Response;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Dispatcher\Exception as DispatchException;

class DispatchEventPlugin
{
    /**
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @param Exception $exception
     * @return bool
     */
    public function beforeException(Event $event, Dispatcher $dispatcher, Exception $exception)
    {
        $di = $dispatcher->getDI();

        // Rollback open transactions
        $db = $di->get('db');
        while ($db->isUnderTransaction()) {
            // Rollback transaction
            $db->rollback();
        }

        // prepare error message
        $error = 'This broken';
        if (APPLICATION_ENV !== 'production') {
            $error = '<h2>' . $exception->getMessage() . '</h2>' . PHP_EOL . PHP_EOL . '<pre>' . $exception->getTraceAsString() . '</pre>';
        }

        // Default error code
        $errorCode = '403';

        // Handle 404 exceptions
        if ($exception instanceof DispatchException) {
            $errorCode = '404';
        }

        $view = $di->get('view');
        $view->setViewsDir(VIEW_PATH);
        $view->start();

        // rendering
        $view->render(
            'errors',
            $errorCode,
            [
                'message' => $error,
            ]
        );

        // finishes the render process by stopping the output buffering
        $view->finish();

        $response = new Response();

        // Prepare response massage
        $response->setContent($view->getContent());
        $response->setStatusCode(403);
        $response->send();

        return false;
    }
}