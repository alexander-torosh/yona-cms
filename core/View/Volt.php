<?php

namespace Core\View;

class Volt extends \Phalcon\Mvc\View\Engine\Volt
{
    public function initCompiler(): void
    {
        $compiler = $this->getCompiler();

        $compiler->addFunction('const', function($resolvedArgs) {
            return $resolvedArgs;
        });

        $compiler->addFilter('md5', 'md5');
    }
}