<?php

namespace Core\Interfaces;

use Phalcon\Mvc\Router\GroupInterface;

interface RoutesInterface
{
    public function init(GroupInterface $group): GroupInterface;
}
