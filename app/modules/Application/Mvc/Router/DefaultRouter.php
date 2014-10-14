<?php

/**
 * Default
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Application\Mvc\Router;

use Phalcon\Mvc\Router;
use Cms\Model\Language;

class DefaultRouter extends Router
{

    public function __construct()
    {
        parent::__construct();

        $this->setDefaultModule('index');
        $this->setDefaultController('index');
        $this->setDefaultAction('index');

        $this->add('/:module/:controller/:action/:params', array(
            'module' => 1,
            'controller' => 2,
            'action' => 3,
            'params' => 4
        ))->setName('default');
        $this->add('/:module/:controller', array(
            'module' => 1,
            'controller' => 2,
            'action' => 'index',
        ))->setName('default_action');
        $this->add('/:module', array(
            'module' => 1,
            'controller' => 'index',
            'action' => 'index',
        ))->setName('default_controller');

    }

    public function addML($pattern, $paths = null, $name)
    {
        $languages = Language::findCachedLanguages();

        foreach ($languages as $lang) {
            $iso = $lang->getIso();
            if ($lang->getPrimary()) {
                $this->add($pattern, $paths)->setName($name . '_' . $iso);
            } else {
                $new_pattern = '/' . $lang->getUrl() . $pattern;
                $paths['lang'] = $iso; // будущее значение константы LANG
                $this->add($new_pattern, $paths)->setName($name . '_' . $iso);
            }
        }
    }

}
