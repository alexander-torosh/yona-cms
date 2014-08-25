<?php

/**
 * ErrorController
 * @copyright Copyright (c) 2011 - 2012 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Index\Controller;

use Application\Mvc\Controller;

class ErrorController extends Controller
{

    public function error404Action()
    {
        $this->response->setHeader(404, 'Not Found');
        $this->view->e = $this->dispatcher->getParam('e');
    }

    public function error503Action()
    {
        $this->response->setHeader(503, 'Service Unavailable');
        $this->view->e = $this->dispatcher->getParam('e');
    }

}