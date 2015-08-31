<?php

/**
 * Routes
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Sitemap;


class Routes
{

    public function init($router)
    {

        $router->addML('/sitemap.xml', array(
            'module' => 'sitemap',
            'controller' => 'index',
            'action' => 'index'
        ), 'sitemap');

        return $router;
    }

}