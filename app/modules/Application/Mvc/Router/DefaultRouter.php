<?php

/**
 * Default
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Application\Mvc\Router;

use Application\Mvc\Helper\CmsCache;
use Phalcon\Mvc\Router;
use Cms\Model\Language;

class DefaultRouter extends Router
{

    const ML_PREFIX = 'ml__';

    public function __construct()
    {
        parent::__construct();

        $this->setDefaultController('index');
        $this->setDefaultAction('index');

        $this->add('/:module/:controller/:action/:params', [
            'module' => 1,
            'controller' => 2,
            'action' => 3,
            'params' => 4
        ])->setName('default');
        $this->add('/:module/:controller', [
            'module' => 1,
            'controller' => 2,
            'action' => 'index',
        ])->setName('default_action');
        $this->add('/:module', [
            'module' => 1,
            'controller' => 'index',
            'action' => 'index',
        ])->setName('default_controller');

    }

    public function addML($pattern, $paths = null, $name)
    {
        $languages = CmsCache::getInstance()->get('languages');

        foreach ($languages as $lang) {
            $iso = $lang['iso'];
            if ($lang['primary']) {
                $this->add($pattern, $paths)->setName(self::ML_PREFIX . $name . '_' . $iso);
            } else {
                $new_pattern = '/' . $lang['url'] . $pattern;
                $paths['lang'] = $iso; // будущее значение константы LANG
                $this->add($new_pattern, $paths)->setName(self::ML_PREFIX . $name . '_' . $iso);
            }
        }
    }

}
