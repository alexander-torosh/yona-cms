<?php

namespace Core\Domain;

use Phalcon\Filter\FilterFactory;
use Phalcon\Filter\FilterInterface;

class DomainFilterFactory
{
    private static $filterLocator;

    protected static function getFilterLocator(): FilterInterface
    {
        if (!self::$filterLocator) {
            $factory = new FilterFactory();

            self::$filterLocator = $factory->newInstance();
        }

        return self::$filterLocator;
    }
}
