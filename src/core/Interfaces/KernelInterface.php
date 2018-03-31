<?php

namespace Core\Interfaces;

interface KernelInterface
{
    public function init(array $modules, array $config): void;

    public function run(): void;

    public function getPrefix(): string;
}
