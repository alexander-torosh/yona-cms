<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Web;

use Phalcon\Di\AbstractInjectionAware;
use Phalcon\Di\DiInterface;
use Phalcon\Events\Manager;
use Phalcon\Mvc\Router as PhalconRouter;
use Phalcon\Mvc\Router\Group;

class Router extends AbstractInjectionAware
{
    // @var $router Router
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
    public function getRouter(): PhalconRouter
    {
        return $this->router;
    }

    private function init()
    {
        $router = new PhalconRouter();
        $router
            ->setDefaultModule('front')
            ->setDefaultController('index')
            ->setDefaultAction('index')
        ;

        // Frontend Router Groups
        $router->mount($this->frontIndex());

        // Backend Router Groups
        $router->mount($this->dashboardIndex());
        $router->mount($this->dashboardAuth());

        $this->router = $router;
    }

    private function frontIndex(): Group
    {
        $group = new Group([
            'controller' => 'index',
        ]);
        $group->setPrefix('/');

        $group->add('', ['action' => 'index'])->setName('homepage');

        return $group;
    }

    private function dashboardIndex(): Group
    {
        $group = new Group([
            'module' => 'dashboard',
            'controller' => 'index',
        ]);
        $group->setPrefix('/dashboard');

        $group->addGet('', ['action' => 'index'])->setName('dashboardIndex');

        return $group;
    }

    private function dashboardAuth(): Group
    {
        $group = new Group([
            'module' => 'dashboard',
            'controller' => 'auth',
        ]);
        $group->setPrefix('/dashboard/auth');

        $group->addGet('/login', ['action' => 'login'])->setName('dashboardLogin');

        return $group;
    }
}
