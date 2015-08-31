<?php
    /**
     * @copyright Copyright (c) 2011 - 2014 Oleksandr Torosh (http://wezoom.net)
     * @author Oleksandr Torosh <web@wezoom.net>
     */

namespace YonaCMS\Plugin;

use \Phalcon\Mvc\User\Plugin;

class Title extends Plugin
{

    public function __construct($di)
    {
        $helper = $di->get('helper');
        if (!$helper->meta()->get('seo-manager')) {
            $helper->title($helper->translate('SITE NAME'));
        }
    }

} 