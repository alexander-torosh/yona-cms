<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Web;

use Phalcon\Acl\Adapter\Memory;
use Phalcon\Di\AbstractInjectionAware;
use Phalcon\Di\DiInterface;
use Phalcon\Events\Event;
use Phalcon\Events\Manager;
use Phalcon\Mvc\Dispatcher;
use Web\Exceptions\AccessDeniedException;

class WebEventsManager extends AbstractInjectionAware
{
    /* @var $eventsManager Manager */
    private $eventsManager;

    public function __construct(DiInterface $container)
    {
        $this->setDI($container);
        $this->init();
    }

    public function getEventsManager(): Manager
    {
        return $this->eventsManager;
    }

        /**
         * @return Manager
         */
    private function init()
    {
        $eventsManager = new Manager();

        $eventsManager->attach('dispatch:beforeExecuteRoute', function (Event $event, Dispatcher $dispatcher) {
            return $this->dispatchBeforeExecuteRoute($dispatcher);
        });

        $this->eventsManager = $eventsManager;
    }

    /**
     * @param Dispatcher $dispatcher
     * @throws AccessDeniedException
     */
    private function dispatchBeforeExecuteRoute(Dispatcher $dispatcher)
    {
        $container = $this->getDI();

        // Acl annotations
        $this->handleControllerAccessAnnotations($container, $dispatcher);
    }

    private function handleControllerAccessAnnotations(DiInterface $container, Dispatcher $dispatcher)
    {
        /* @var $acl Memory */
        $acl = $container->get('acl');

        // @TODO Replace with real value
        $sessionRole = 'guest';

        /* @var $annotations \Phalcon\Annotations\Adapter\Memory */
        $annotations = $container->get('annotations');

        $controllerName = $dispatcher->getControllerClass();
        $actionName     = $dispatcher->getActionName()
            . 'Action';

        $acl->addComponent($controllerName, $actionName);

        $data = $annotations->getMethod($controllerName, $actionName);

        if ($data->has('Access')) {
            $annotationAccess = $data->get('Access');
            $accessRoles = $annotationAccess->getArguments();

            if (!empty($accessRoles)) {
                foreach ($accessRoles as $role) {
                    $acl->allow($role, $controllerName, $actionName);
                }
            }
        }

        if (!$acl->isAllowed($sessionRole, $controllerName, $actionName)) {
            throw new AccessDeniedException("Sorry, but you don't have an access to this page.");
        }
    }
}