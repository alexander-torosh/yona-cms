<?php

namespace Core;

use Phalcon\Mvc\Application;

abstract class KernelAbstract extends Application
{
    abstract public function init(array $modules, array $config);

    abstract public function run(): void;

    abstract public function getPrefix(): string;
}
