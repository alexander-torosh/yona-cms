<?php

namespace Core;

use Phalcon\Http\Response;

class BaseController extends \Phalcon\Mvc\Controller
{

    /**
     * @param array $data
     * @param int   $status
     *
     * @return Response
     */
    public function json(array $data, int $status = 200): Response
    {
        $this->view->disable();

        $response = new Response();

        $response->setJsonContent($data);
        $response->setStatusCode($status);
        $response->send();

        return $response;
    }
}