<?php

namespace Core\Service;

use Core\Interfaces\CollectionsInterface;
use Core\MicroAbstract;
use Phalcon\Mvc\Micro\Collection as MicroCollection;

class CollectionsLoaderService
{
    public function include(MicroAbstract $kernel): void
    {
        $di = $kernel->getDI();
        /** @var array $modules */
        $modules = $di->get('modules');

        foreach ($modules as $module) {
            /** @var MicroCollection $collection */
            foreach ($this->make($kernel, $module) as $collection) {
                $kernel->mount($collection);
            }
        }
    }

    /**
     * @param MicroAbstract $kernel
     * @param string        $module
     *
     * @return \Generator
     */
    private function make(MicroAbstract $kernel, string $module): \Generator
    {
        // make the base namespace
        $kernelPrefix = ucfirst($kernel->getPrefix());
        $baseNamespace = ucfirst($module) . '\\' . $kernelPrefix;
        // the collections for the module
        $class = $baseNamespace . '\\' . 'Collections';

        if (class_exists($class)) {

            /** @var CollectionsInterface $moduleCollections */
            $moduleCollections = new $class;

            // get routes
            $collections = $moduleCollections->init();
            foreach ($collections as $handler => $endpoints) {
                // if the endpoints have a bad format - skip them
                if (!\is_array($endpoints)) {
                    continue;
                }

                // create new collection
                $microCollection = new MicroCollection();
                $microCollection->setHandler($handler, true);

                // set a common prefix for all routes
                $microCollection->setPrefix('/'.$module);

                /** @var string $endpoint */
                /** @var array $methods */
                foreach ($endpoints as $endpoint => $methods) {
                    foreach ($methods as $method => $action) {
                        // choose the http method
                        switch ($method) {
                            case CollectionsInterface::METHOD_GET:
                                $microCollection->get($endpoint, $action);
                                break;
                            case CollectionsInterface::METHOD_POST:
                                $microCollection->post($endpoint, $action);
                                break;
                        }
                    }
                }

                yield $microCollection;
            }
        }
    }
}