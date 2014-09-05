<?php

/**
 * Routes
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Slider;

class Routes
{

    public function init($router)
    {
        $router->add('/slider/{id:[0-9]+}', array(
            'module' => 'slider',
            'controller' => 'index',
            'action' => 'slider',
        ))->setName('sliders');
//
//        $router->add('/slider/{id:\d+}', array(
//            'module' => 'slider',
//            'controller' => 'index',
//            'action' => 'slider',
//        ))->setName('slider');

        return $router;

    }

}