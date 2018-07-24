<?php

namespace Core\Interfaces;

use Core\MicroAbstract;

interface MicroModuleInterface
{
    public function initialize(MicroAbstract $app): void;
}
