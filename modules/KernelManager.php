<?php

namespace Modules;

use Core\KernelAbstract;

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

        $this->modules = [
            'user'
        ];
    }

    public function setKernel(KernelAbstract $kernel): void
    {
        $this->kernel = $kernel;

        $this->kernel->init($this->modules, $this->config);
    }

    public function handle(): void
    {
        $this->kernel->run();
    }
}
