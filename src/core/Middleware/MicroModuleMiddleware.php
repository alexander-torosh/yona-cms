<?php

namespace Core\Middleware;

use Application\MicroKernel;
use Phalcon\Events\Event;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\MiddlewareInterface;
use Core\MicroModules;

class MicroModuleMiddleware implements MiddlewareInterface
{
    /**
     * @param Event $event
     * @param MicroKernel $application
     * @return bool
     * @throws \Phalcon\Exception
     */
    public function beforeHandleRoute(Event $event, MicroKernel $application): bool
    {
        $modules = $application->getDI()->get('modules');
        $modules->mountRoutes($application);

        return true;
    }

    /**
     * @param Event $event
     * @param MicroKernel $application
     * @return bool
     * @throws \Phalcon\Exception
     */
    public function beforeExecuteRoute(Event $event, MicroKernel $application): bool
    {
        /** @var Micro\LazyLoader[] $handler */
        $activeHandler = $application->getActiveHandler();

        if (!empty($activeHandler)) {
            $handler = $activeHandler[0];
            $controller = $handler->getDefinition();

            /** @var MicroModules $modules */
            $modules = $application->getDI()->get('modules');
            $modules->loadModule($application, $controller);
        }

        return true;
    }

    /**
     * Calls the middleware
     *
     * @param Micro $application
     *
     * @return bool
     */
    public function call(Micro $application): bool
    {
        return true;
    }
}
