<?php

/**
 * Admin localization
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace YonaCMS\Plugin;

use Phalcon\Mvc\User\Plugin;

class AdminLocalization extends Plugin
{

    public function __construct($config)
    {
        $file = APPLICATION_PATH . '/../data/translations/admin/' . $config->admin_language . '.php';
        if (!is_file($file)) {
            die("file $file not exists");
        }
        $translations = include($file);
        $this->getDI()->set('admin_translate', new \Phalcon\Translate\Adapter\NativeArray(array('content' => $translations)));

    }

}
