<?php

namespace Dashboard;

use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;

class ControllerAbstract extends Controller
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