<?php

/**
 * Routes
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Publication;

use Publication\Model\Publication;

class Routes
{

    public function init($router)
    {
        $types_keys = array_keys(Publication::$types);
        $types_regex = '(' . implode('|', $types_keys) . ')';

        $router->addML('/{type:' . $types_regex . '}', array(
            'module' => 'publication',
            'controller' => 'index',
            'action' => 'index'
        ),'publications');

        $router->addML('/{type:' . $types_regex . '}/{slug:[a-zA-Z0-9_-]+}.html', array(
            'module' => 'publication',
            'controller' => 'index',
            'action' => 'publication'
        ),'publication');

        return $router;

    }

}