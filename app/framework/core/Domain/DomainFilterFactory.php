<?php

namespace Core\Domain;

use Phalcon\Filter\FilterFactory;
use Phalcon\Filter\FilterInterface;

class DomainFilterFactory
{
    protected static function getFilterLocator(): FilterInterface
    {
        $factory = new FilterFactory();

        return $factory->newInstance();
    }
}
