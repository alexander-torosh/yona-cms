<?php

/**
 * @author Oleksandr Torosh <webtorua@gmail.com>
 */
namespace Yona\View;

use Yona\View\Engine\Volt;

class View extends \Phalcon\Mvc\View
{

    public function __construct($options = null)
    {
        parent::__construct($options);

        $this->registerEngines([
            '.phtml' => new \Phalcon\Mvc\View\Engine\Php($this, $this->getDI()),
            '.volt'  => new Volt($this, $this->getDi()),
        ]);
    }

}