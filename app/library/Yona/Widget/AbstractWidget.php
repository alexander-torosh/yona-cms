<?php

/**
 * WidgetAbstract
 * @copyright Copyright (c) 2011 - 2012 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Yona\Widget;

class AbstractWidget extends \Phalcon\Mvc\User\Component
{

    private $module;

    public function widgetPartial($template, array $data = array())
    {
        return $this->helper->modulePartial($template, $data, $this->module);

    }

    public function setModule($module)
    {
        $this->module = $module;
    }

}
