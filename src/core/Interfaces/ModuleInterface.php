<?php

namespace Core\Interfaces;

interface ModuleInterface
{
    /** Requirements modules */
    public function requirements(): array;
}
