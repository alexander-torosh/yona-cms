<?php

/**
 * Meta
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Application\Mvc\Helper;

class Title extends \Phalcon\Mvc\User\Component
{

    private static $instance;
    private static $parts;
    private static $separator = ' | ';

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Title();
        }
        return self::$instance;

    }

    public function prepend($string)
    {
        array_unshift(self::$parts, $string);
    }

    public function append($string)
    {
        self::$parts[] = $string;
    }

    public function get()
    {
        if (!empty(self::$parts)) {
            return implode(self::$separator, self::$parts);
        }
    }

}
