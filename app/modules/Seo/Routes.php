<?php

/**
 * Routes
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Seo;

class Routes
{

    public function init($router)
    {
        $router->addML('/seo/admin/robots', array(
            'module' => 'seo',
            'controller' => 'robots',
            'action' => 'index',
        ), 'robots');

        return $router;

    }

}