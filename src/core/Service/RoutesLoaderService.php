<?php

namespace Core\Service;

use Core\Interfaces\RoutesInterface;
use Core\KernelAbstract;
use Phalcon\Mvc\Router;
use Phalcon\Mvc\Router\Group as RouterGroup;

class RoutesLoaderService
{
    public function include(KernelAbstract $kernel): void
    {
        $di = $kernel->getDI();
        /** @var Router $router */
        $router = $di->get('router');

        if (!$router) {
            $router = new Router();
        }

        $modules = $kernel->getModules();
        $kernelPrefix = ucfirst($kernel->getPrefix());
        foreach (array_keys($modules) as $module) {
            $baseNamespace = ucfirst($module) . '\\' . $kernelPrefix;

            // the routes for the module
            $class = $baseNamespace . '\\' . 'Routes';

            if (class_exists($class)) {
                /** @var RoutesInterface $moduleRouter */
                $moduleRouter = new $class;

                $group = new RouterGroup();
                $group->setPaths(
                    [
                        'module'    => $module,
                        'namespace' => $baseNamespace . '\\Controllers',
                    ]
                );

                // All the routes start with the module name
                $group->setPrefix('/' . $module);

                $moduleRouter = $moduleRouter->init($group);

                // mount router to kernel
                $router->mount($moduleRouter);
            }
        }

        $di->set('router', $router, true);
        $kernel->setDI($di);
    }
}
