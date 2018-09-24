<?php

namespace Application;

use Core\Interfaces\KernelInterface;
use Core\KernelAbstract;
use Phalcon\Exception;
use Phalcon\Loader;

/**
 * Class KernelManager
 * @package Application
 * @property $kernel KernelAbstract
 * @property $config array
 * @property $modules array
 */
class KernelManager
{
    protected $kernel;

    protected $config;

    protected $modules;

    public function __construct(array $config = [])
    {
        $this->config = $config;

        try {
            /**
             * The list of enabled Modules.
             * Modules order is important.
             * Each Module could has own dependencies on other Modules and initializes in the defined order.
             */
            $modulesJson = file_get_contents(CONFIG_PATH . 'modules.json');
            $this->modules = json_decode($modulesJson, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Wrong module.json format!');
            }

            $namespaces = [];
            foreach ($this->modules as $module) {
                $namespaces[$module] = MODULES_PATH . $module . '/src';
            }

            $loader = new Loader();
            $loader->registerNamespaces($namespaces);
            $loader->register();

        } catch (Exception $e) {
            throw $e;
        }
    }

    public function setKernel(KernelInterface $kernel): void
    {
        $this->kernel = $kernel;
        $this->kernel->init($this->modules, $this->config);
    }

    public function handle(): void
    {
        $this->kernel->run();
    }
}
