<?php

/**
 * Volt
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Application\Mvc\View\Engine;

class Volt extends \Phalcon\Mvc\View\Engine\Volt
{

    public function initCompiler()
    {
        $compiler = $this->getCompiler();

        $compiler->addFunction('helper', function () {
            return '$this->helper';
        });
        $compiler->addFunction('translate', function ($resolvedArgs) {
            return '$this->helper->translate(\'.$resolvedArgs.\')';
        });
        $compiler->addFunction('langUrl', function ($resolvedArgs) {
            return '$this->helper->langUrl(' . $resolvedArgs . ')';
        });
        $compiler->addFunction('image', function ($resolvedArgs) {
            return '(new \Image\Storage(' . $resolvedArgs . '))';
        });
        $compiler->addFunction('widget', function ($resolvedArgs) {
            return '(new \Application\Widget\Proxy(' . $resolvedArgs . '))';
        });

        $compiler->addFunction('substr', 'substr');

    }

}
