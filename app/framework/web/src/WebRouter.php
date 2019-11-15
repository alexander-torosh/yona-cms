<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Web;

use Phalcon\Mvc\Router;

class WebRouter
{
    /**
     * @return Router
     */
    public function init(): Router
    {
        $router = new Router();
        $router
            ->setDefaultModule('front')
            ->setDefaultController('index')
            ->setDefaultAction('index');

        // Frontend Router Groups
        $router->mount($this->frontIndex());

        // Backend Router Groups
        $router->mount($this->dashboardIndex());

        return $router;
    }

    /**
     * @return Router\Group
     */
    private function frontIndex(): Router\Group
    {
        $group = new Router\Group([
            'controller' => 'index',
        ]);
        $group->setPrefix('/');

        $group->add('', ['action' => 'index']);

        return $group;
    }

    private function dashboardIndex(): Router\Group
    {
        $group = new Router\Group([
            'module' => 'dashboard',
            'controller' => 'index',
        ]);
        $group->setPrefix('/dashboard');

        $group->add('', ['action' => 'index']);

        return $group;
    }
}