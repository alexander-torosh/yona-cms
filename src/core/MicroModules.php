<?php

namespace Core;

use Core\Interfaces\CollectionsInterface;
use Core\Interfaces\MicroModuleInterface;
use Core\MicroAbstract;
use Phalcon\Application\Exception;

class MicroModules
{
    private $modules = [];

    public function init(MicroAbstract $kernel, array $modules): void
    {
        // make the base namespace
        $kernelPrefix = ucfirst($kernel->getPrefix());

        foreach ($modules as $module) {
            $baseNamespace = ucfirst($module) . '\\' . $kernelPrefix;
            // the collections for the module
            $mClass = $baseNamespace . '\\' . 'Module';
            $cClass = $baseNamespace . '\\' . 'Collections';

            // collection class is required
            if (class_exists($cClass)) {
                $this->modules[$module] = [
                    'module' => class_exists($mClass) ? $mClass : null,
                    'collections' => $cClass,
                    'handlers' => [],
                ];
            }
        }
    }

    /**
     * Mount all collections to app
     *
     * @param MicroAbstract $kernel
     * @throws Exception
     */
    public function mountRoutes(MicroAbstract $kernel): void
    {
        // for all modules
        foreach ($this->modules as $moduleName => $module) {
            // gets route class
            ['collections' => $collectionClass] = $module;

            /** @var CollectionsInterface $route */
            $route = new $collectionClass();

            // checks the implementation of the interface
            if ($route instanceof CollectionsInterface === false) {
                throw new Exception("route $route must be instanceof CollectionsInterface");
            }

            // gets all collections for the module
            $collections = $route->collections();

            // mount collections
            foreach ($collections as $collection) {
                $kernel->mount($collection);

                // attach the handler to the module
                $this->addHandler($moduleName, $collection->getHandler());
            }
        }
    }

    /**
     * Attach the handler to the module
     *
     * @param string $module
     * @param string $handler
     */
    private function addHandler(string $module, string $handler): void
    {
        if (array_key_exists($module, $this->modules)) {
            $this->modules[$module]['handlers'][] = $handler;
        }
    }

    /**
     * Initializes the module by the handler
     *
     * @param MicroAbstract $kernel
     * @param string $handler
     * @throws Exception
     */
    public function loadModule(MicroAbstract $kernel, string $handler): void
    {
        // search module
        foreach ($this->modules as $module) {
            // skip empty or not initialized modules
            if (empty($module['handlers']) || empty($module['module'])) {
                continue;
            }

            // if handler is found
            if (\in_array($handler, $module['handlers'], true)) {
                ['module' => $moduleClass] = $module;

                /** @var MicroModuleInterface $module */
                $module = new $moduleClass();

                if ($module instanceof MicroModuleInterface === false) {
                    throw new Exception("module $moduleClass must be instanceof MicroModuleInterface");
                }

                // init module
                $module->initialize($kernel);
            }
        }
    }
}
