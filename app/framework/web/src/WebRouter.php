<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Web;

use Phalcon\Di\AbstractInjectionAware;
use Phalcon\Di\DiInterface;
use Phalcon\Events\Manager;
use Phalcon\Mvc\Router;

class WebRouter extends AbstractInjectionAware
{
    /* @var $router Router */
    private $router;

    public function __construct(DiInterface $container, Manager $eventsManager)
    {
        $this->setDI($container);
        $this->init();
        $this->router->setEventsManager($eventsManager);
    }

    /**
     * @return mixed
     */
    public function getRouter(): Router
    {
        return $this->router;
    }

    private function init()
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

        $this->router = $router;
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