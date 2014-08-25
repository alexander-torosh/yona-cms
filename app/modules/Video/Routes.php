<?php

/**
 * Routes
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Video;

class Routes
{

    public function init($router)
    {
        $router->add('/video', array(
            'module' => 'video',
            'controller' => 'index',
            'action' => 'index',
            'id' => 1,
        ))->setName('video');

        $router->add('/video/{id:\d+}', array(
            'module' => 'video',
            'controller' => 'index',
            'action' => 'index',
        ))->setName('video_id');

        return $router;

    }

}