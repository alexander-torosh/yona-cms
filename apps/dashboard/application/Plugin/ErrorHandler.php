<?php

namespace Dashboard\Application\Plugin;

use Phalcon\Db\Adapter;
use Phalcon\Di\InjectionAwareInterface;
use Phalcon\Events\Event;
use Phalcon\Dispatcher;
use Phalcon\Http\Request;
use Phalcon\Http\Response;
use Phalcon\Mvc\User\Plugin;

class ErrorHandler extends Plugin
{
    public function beforeException(Event $event, Dispatcher $dispatcher, \Exception $exception): bool
    {
        // Rollback open transactions
        $this->closeOpenTransaction();

        $request = new Request();

        // For Ajax Request
        if ($request->isAjax()) {
            $response = new Response();

            // Prepare response massage
            $data = [
                'error' => $exception->getMessage(),
            ];

            $response->setJsonContent($data);
            $response->setStatusCode(403);
            $response->send();

            $this->view->disable();
        } else if (APPLICATION_ENV !== 'production') {

            echo '<p>' . $exception->getMessage() . '</p>';

            echo '<br />', PHP_EOL;

            echo $exception->getTraceAsString();
            die;

        } else {
            $response = new Response();
            $response->redirect(['for' => 'error-404']);
            $response->send();
        }

        return false;
    }

    private function closeOpenTransaction(): void
    {
        /** @var Adapter $db */
        $db = $this->getDI()->get('db');

        while($db->isUnderTransaction()) {
            // Rollback transaction
            $db->rollback();
        }
    }
}
