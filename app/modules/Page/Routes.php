<?php

/**
 * Routes
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Page;

use Application\Mvc\Router\DefaultRouter;

class Routes
{

    public function init(DefaultRouter $router)
    {
        $router->addML('/{slug:[a-zA-Z0-9_-]+}.html', array(
            'module' => 'page',
            'controller' => 'index',
            'action' => 'index'
        ), 'page');

        $router->addML('/contacts.html', array(
            'module' => 'page',
            'controller' => 'index',
            'action' => 'contacts',
        ), 'contacts');

        return $router;

    }

}