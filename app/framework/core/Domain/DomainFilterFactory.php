<?php

namespace Core\Domain;

use Phalcon\Filter\FilterFactory;

class DomainFilterFactory
{
    protected $filterLocator;

    public function __construct()
    {
        $factory = new FilterFactory();
        $this->filterLocator = $factory->newInstance();
    }
}
