<?php

namespace Application;

use Core\Interfaces\KernelInterface;
use Core\KernelAbstract;
use Phalcon\Loader;

class KernelManager
{
    /** @var KernelAbstract */
    protected $kernel;

    /** @var array */
    protected $config;

    /** @var array */
    protected $modules;

    public function __construct(array $config = [])
    {
        $this->config = $config;

        // load modules.json
        $string = file_get_contents(__DIR__ . '/../modules.json');
        $this->modules = json_decode($string, true);

        // register namespaces
        $namespaces = [];
        foreach ($this->modules as $module) {
            $umodule = ucfirst($module);
            $namespaces[$umodule] = MODULES_PATH . '/' . ucfirst($module);
        }

        $loader = new Loader();
        $loader->registerNamespaces($namespaces);
        $loader->register();
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
