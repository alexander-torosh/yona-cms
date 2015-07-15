<?php

/**
 * Localization
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace YonaCMS\Plugin;

use Application\Mvc\Helper\CmsCache;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;

class Localization extends Plugin
{

    public function __construct(Dispatcher $dispatcher)
    {
        $cmsCache = new CmsCache();
        $languages = $cmsCache->get('languages');

        $defaultLangArray = array_values(array_slice($languages, 0, 1));
        $defaultLang = $defaultLangArray[0];

        $request = $this->getDI()->get('request');
        $queryLang = $request->getQuery('lang');
        if (!$queryLang) {
            $langParam = $dispatcher->getParam('lang');
        } else {
            $langParam = $queryLang;
        }

        if (!$langParam) {
            $langParam = $defaultLang['iso'];
        }

        foreach ($languages as $language) {
            if ($langParam == $language['iso']) {
                define('LANG', $language['iso']);
                define('LANG_URL', '/' . $language['url']);
            }
        }
        if (!defined('LANG')) {
            define('LANG', $defaultLang['iso']);
            \Application\Mvc\Model\Model::$lang = $defaultLang['iso'];
        }
        if (!defined('LANG_URL')) {
            define('LANG_URL', $defaultLang['url']);
        }

        $translations = \Cms\Model\Translate::findCachedByLangInArray(LANG);
        $this->getDI()->set('translate', new \Phalcon\Translate\Adapter\NativeArray(['content' => $translations]));
    }
}