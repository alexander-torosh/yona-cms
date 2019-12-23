<?php

namespace Core\Domain;

use Phalcon\Di\AbstractInjectionAware;

class DomainRepository extends AbstractInjectionAware
{
    public function __construct($di)
    {
        $this->setDI($di);
    }
}
