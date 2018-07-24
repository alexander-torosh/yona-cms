<?php

namespace Application\Front\Plugin;

use Phalcon\Db\Adapter;
use Phalcon\Http\Response;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\User\Plugin;

class ErrorHandler extends Plugin
{
    public function handle(Micro $app, \Throwable $exception): bool
    {
        // Rollback open transactions
        $this->closeOpenTransaction();

        // prepare error message
        $error = 'This broken';
        if (APPLICATION_ENV !== 'production') {
            $error = $exception->getMessage();
        }

        $view = $app->getDI()->get('view');
        // view settings
        $view->setViewsDir(VIEW_PATH);
        // starts rendering process enabling the output buffering
        $view->start();

        // rendering
        $view->render(
            'errors',
            '500',
            [
                'message' => $error,
            ]
        );

        // finishes the render process by stopping the output buffering
        $view->finish();

        // gets content
        $content = $view->getContent();

        $response = new Response();

        // Prepare response massage
        $response->setContent($content);
        $response->setStatusCode(403);
        $response->send();

        return false;
    }

    private function closeOpenTransaction(): void
    {
        /** @var Adapter $db */
        $db = $this->getDI()->get('db');
        while ($db->isUnderTransaction()) {
            // Rollback transaction
            $db->rollback();
        }
    }
}
