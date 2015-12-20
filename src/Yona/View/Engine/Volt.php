<?php

/**
 * @author Oleksandr Torosh <webtorua@gmail.com>
 */
namespace Yona\View\Engine;

class Volt extends \Phalcon\Mvc\View\Engine\Volt
{

    public function __construct($view, $dependencyInjector)
    {
        parent::__construct($view, $dependencyInjector);

        $this->setOptions(['compiledPath' => getenv('BASE_PATH') . getenv('VOLT_CACHE_PATH')]);
        $this->getCompiler()
            ->addFunction('getenv', function ($resolvedArgs, $exprArgs) {
                return 'getenv(' . $resolvedArgs . ')';
            });;
    }

}