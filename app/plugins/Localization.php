<?php

/**
 * Localization
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace YonaCMS\Plugin;

use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;

class Localization extends Plugin
{

    public function __construct(Dispatcher $dispatcher)
    {
        $languages = \Cms\Model\Language::findCachedLanguages();
        $defaultLang = $languages[0];

        $request = $this->getDI()->get('request');
        $queryLang = $request->getQuery('lang');
        if (!$queryLang) {
            $langParam = $dispatcher->getParam('lang');
        } else {
            $langParam = $queryLang;
        }

        if (!$langParam) {
            $langParam = $defaultLang->getIso();
        }

        foreach ($languages as $language) {
            if ($langParam == $language->getIso()) {
                define('LANG', $language->getIso());
                define('LANG_URL', '/'.$language->getUrl());
            }
        }
        if (!defined('LANG')) {
            define('LANG', $defaultLang->getIso());
        }
        if (!defined('LANG_URL')) {
            define('LANG_URL', $defaultLang->getUrl());
        }

        $translations = \Cms\Model\Translate::findCachedByLangInArray(LANG);
        $this->getDI()->set('translate', new \Phalcon\Translate\Adapter\NativeArray(array('content' => $translations)));

    }

}
