<?php

namespace Application\Api\Plugin;

use Phalcon\Db\Adapter;
use Phalcon\Http\Response;
use Phalcon\Mvc\User\Plugin;

class ErrorHandler extends Plugin
{
    public function handle(\Exception $exception)
    {
        // rollback open transactions
        $this->closeOpenTransaction();

        // prepare response massage
        $error = 'Oops...';

        // for development return the message
        if (APPLICATION_ENV !== 'production') {
            $error = $exception->getMessage();
        }

        $response = new Response();
        $response->setJsonContent([
            'error' => $error,
        ]);
        $response->setStatusCode(403);
        $response->send();

        return false;
    }

    private function closeOpenTransaction(): void
    {
        /** @var Adapter $db */
        $db = $this->getDI()->get('db');
        while ($db->isUnderTransaction()) {
            // rollback transaction
            $db->rollback();
        }
    }
}
