<?php
 /**
 * @copyright Copyright (c) 2011 - 2014 Oleksandr Torosh (http://wezoom.net)
 * @author Oleksandr Torosh <web@wezoom.net>
 */

namespace Slider;

class Init
{

    public function init($di)
    {
        // CSS
        $assets = $di->get('assets');

        $assets->collection('modules-css')
            ->addCss(__DIR__ . '/assets/main.css');

        $assets->collection('modules-admin-css')
            ->addCss(__DIR__ . '/assets/admin.css');
    }

} 