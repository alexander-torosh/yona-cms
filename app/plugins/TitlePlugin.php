<?php
 /**
 * @copyright Copyright (c) 2011 - 2014 Oleksandr Torosh (http://wezoom.net)
 * @author Oleksandr Torosh <web@wezoom.net>
 */

use \Phalcon\Mvc\User\Plugin;

class TitlePlugin extends Plugin
{

    public function __construct($di)
    {
        $helper = $di->get('helper');
        $helper->title($helper->translate('SITE NAME'));
    }

} 