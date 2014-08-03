<?php

/**
 * Localization
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */
use Phalcon\Mvc\User\Plugin,
    Phalcon\Mvc\Dispatcher;

class LocalizationPlugin extends Plugin
{

    public function __construct(Dispatcher $dispatcher)
    {
        $request   = $this->getDI()->get('request');
        $queryLang = $request->getQuery('lang');
        if (!$queryLang) {
            $lang = $dispatcher->getParam('lang');
        } else {
            $lang = $queryLang;
        }

        switch ($lang) {
            case 'uk' :
                define('LANG', 'uk');
                define('LANG_SUFFIX', '_uk');
                define('LANG_URL', '/uk');
                define('LOCALE', 'uk_UA');
                break;
            case 'en' :
                define('LANG', 'en');
                define('LANG_SUFFIX', '_en');
                define('LANG_URL', '/en');
                define('LOCALE', 'en_EN');
                break;
            default:
                define('LANG', 'ru');
                define('LANG_SUFFIX', '');
                define('LANG_URL', '/');
                define('LOCALE', 'ru_RU');
        }

        Locale::setDefault(LOCALE);

        $this->getDI()->set('translate', new \Application\Localization\GettextAdapter(array(
            'locale'    => LOCALE,
            'lang'      => LANG,
            'file'      => 'messages',
            'directory' => APPLICATION_PATH . '/lang'
        )));

    }

}
