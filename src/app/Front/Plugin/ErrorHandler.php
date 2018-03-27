<?php

namespace Application\Front\Plugin;

use Dashboard\Application\Exception\AjaxException;
use Phalcon\Db\Adapter;
use Phalcon\Di\InjectionAwareInterface;
use Phalcon\Events\Event;
use Phalcon\Dispatcher;
use Phalcon\Http\Request;
use Phalcon\Http\Response;
use Phalcon\Mvc\User\Plugin;

class ErrorHandler extends Plugin
{
    public function beforeException(Event $event, Dispatcher $dispatcher, \Exception $exception)
    {
        // Rollback open transactions
        $this->closeOpenTransaction();

        $response = new Response();

        // Prepare response massage
        $response->setContent($exception->getMessage());
        $response->setStatusCode(404);
        $response->send();

        return false;
    }

    private function closeOpenTransaction()
    {
        /** @var Adapter $db */
        $db = $this->getDI()->get('db');
        while ($db->isUnderTransaction()) {
            // Rollback transaction
            $db->rollback();
        }
    }
}
