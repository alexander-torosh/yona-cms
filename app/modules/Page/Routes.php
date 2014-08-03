<?php

/**
 * Routes
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Page;

class Routes
{

    public function init($router)
    {
        $router->add('/{slug:[a-zA-Z_-]+}.html',array(
            'module' => 'page',
            'controller' => 'index',
            'action' => 'index'
        ))->setName('page');

        return $router;

    }

}