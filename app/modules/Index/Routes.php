<?php

/**
 * Routes
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Index;

class Routes
{

    public function init($router)
    {
        $router->add('/', array(
            'module' => 'index',
            'controller' => 'index',
            'action' => 'index',
        ))->setName('index');

        $router->add('/contacts', array(
            'module' => 'index',
            'controller' => 'index',
            'action' => 'contacts',
        ));

        return $router;

    }

}