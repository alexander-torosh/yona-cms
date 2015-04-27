<?php
/**
 * @copyright Copyright (c) 2011 - 2014 Oleksandr Torosh (http://wezoom.net)
 * @author Oleksandr Torosh <web@wezoom.net>
 */

namespace Slider\Mvc;

use Slider\Model\Slider;

class Helper extends \Phalcon\Mvc\User\Component
{

    public function slider($id)
    {
        $slider = Slider::findCachedById($id);
        $html = '';

        if ($slider && count($slider->cachedImages())) {
            $view = clone($this->getDI()->get('view'));
            $view->start();
            $view->setViewsDir(__DIR__ . '/../views/');
            $view->setPartialsDir('partials/');
            $view->partial('slider/base', array('slider' => $slider));
            $html = ob_get_contents();
            $view->finish();
        }

        return $html;
    }

} 