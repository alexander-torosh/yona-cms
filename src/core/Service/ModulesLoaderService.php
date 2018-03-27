<?php

namespace Core\Service;

use Core\KernelAbstract;
use Phalcon\Loader;
use ReflectionClass;

class ModulesLoaderService
{
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
