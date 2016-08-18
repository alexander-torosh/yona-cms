<?php
    /**
     * @copyright Copyright (c) 2011 - 2014 Oleksandr Torosh (http://wezoom.net)
     * @author Oleksandr Torosh <web@wezoom.net>
     */

namespace Application\Utils;


class ModuleName
{

    public static function camelize($module)
    {
        $tmpModuleNameArr = explode('-', $module);
        $moduleName = '';
        foreach ($tmpModuleNameArr as $part) {
            $moduleName .= \Phalcon\Text::camelize($part);
        }
        return $moduleName;
    }

} 