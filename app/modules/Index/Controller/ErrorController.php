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
        $this->helper->error(404);
    }

    public function error503Action()
    {
        $this->helper->error(503);
    }

}