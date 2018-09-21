<?php

namespace Core\Service;

use Core\Interfaces\RoutesInterface;
use Core\KernelAbstract;
use Phalcon\Mvc\Router;
use Phalcon\Mvc\Router\Group as RouterGroup;
use Phalcon\Text;

class RoutesLoaderService
{
    public function include (KernelAbstract $kernel): void
    {
        $di = $kernel->getDI();
        /** @var Router $router */
        $router = $di->get('router');

        if (!$router) {
            $router = new Router();
            $router->setDefaults([
                'module'     => 'index',
                'controller' => 'index',
                'action'     => 'index',
            ]);
        }

        $kernelPrefix = ucfirst($kernel->getPrefix());
        $modules      = $kernel->getModules();

        foreach (array_keys($modules) as $module) {
            $baseNamespace = ucfirst($module) . '\\' . $kernelPrefix;
            $moduleName    = Text::uncamelize($module, '-');

            // the routes for the module
            $class = $baseNamespace . '\\' . 'Routes';

            if (class_exists($class)) {
                /** @var RoutesInterface $moduleRouter */
                $moduleRouter = new $class;

                $group = new RouterGroup();
                $group->setPaths(
                    [
                        'module'    => $moduleName,
                        'namespace' => $baseNamespace . '\\Controllers',
                    ]
                );

                // mount router to kernel
                $router->mount($moduleRouter->init($group, $moduleName));
            }
        }

        $di->set('router', $router, true);

        $kernel->setDI($di);
    }
}
