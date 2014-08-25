<?php

/**
 * Routes
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Projects;

class Routes
{

    public function init($router)
    {
        $router->add('/projects', array(
            'module' => 'projects',
            'controller' => 'index',
            'action' => 'index',
        ))->setName('projects');

        $router->add('/project/{id:\d+}', array(
            'module' => 'projects',
            'controller' => 'index',
            'action' => 'project',
        ))->setName('project');

        return $router;

    }

}