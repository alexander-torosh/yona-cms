<?php

namespace Core\Service;

use Core\KernelAbstract;
use Phalcon\Loader;
use ReflectionClass;

class ModulesLoaderService
{
    public function registerNamespaces(KernelAbstract $kernel, array $modules): void
    {
        $namespaces = [];
        $modulePrefix = ucfirst($kernel->getPrefix());
        foreach ($modules as $module) {
            $umodule = ucfirst($module);

            $namespaces[$umodule . '\\' . $modulePrefix] =
                MODULES_PATH . '/' . $umodule . '/' . $modulePrefix;
        }

        $loader = new Loader();
        $loader->registerNamespaces($namespaces);
        $loader->register();
    }

    public function register(KernelAbstract $kernel, array $modules): void
    {
        $normalizeModules = [];
        foreach ($modules as $module) {
            $className = ucfirst($module) . '\\' . ucfirst($kernel->getPrefix()) . '\\' . 'Module';

            try {
                $reflector = new ReflectionClass($className);

                $normalizeModules[$module] = [
                    'className' => $className,
                    'path'      => $reflector->getFileName(),
                ];
            } catch (\ReflectionException $e) {
                continue;
            }

        }

        //Register the installed modules
        $kernel->registerModules($normalizeModules);
    }
}