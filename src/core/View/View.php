<?php

namespace Core\View;

use Phalcon\DiInterface;

class View extends \Phalcon\Mvc\View
{
    public function register(DiInterface $di)
    {
        // Initialize volt as template engine
        $volt = new Volt($this, $di);

        $volt->setOptions([
            'compiledPath'      => BASE_PATH . '/data/cache/volt/',
            'compiledExtension' => '.php',
            'compiledSeparator' => '_',
            'compileAlways'     => false
        ]);

        $volt->initCompiler();

        $this->registerEngines([
            '.volt' => $volt,
        ]);

        return $this;
    }
}
