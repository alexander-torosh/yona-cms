<?php

/**
 * Routes
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Admin;

class Routes
{

    public function init($router)
    {
        $router->add('/admin', array(
            'module'     => 'admin',
            'controller' => 'index',
            'action'     => 'index',
        ))->setName('admin');

        return $router;

    }

}