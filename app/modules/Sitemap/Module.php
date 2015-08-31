<?php

namespace Sitemap;

class Module
{

    public function registerAutoloaders()
    {

    }

    public function registerServices($di)
    {
        $dispatcher = $di->get('dispatcher');
        $dispatcher->setDefaultNamespace("Sitemap\Controller");
        $di->set('dispatcher', $dispatcher);

        /**
         * Setting up the view component
         */
        $view = $di->get('view');
        $view->setViewsDir(__DIR__ . '/views/');

    }
}

//add to modules.php
//add to acl.php
//set access Rights
//delete /web/sitemap.xml if exist


// Также, если языков больше 1, потребуется внести изменения в базовую модель
/*

  AND lang = "' . LANG . '"';       заменить на                      AND lang = "' . self::$lang . '"';     в двух местах (функциии initTranslationsArray и translateCacheKey

добавить функцию

    //Установка языка для особых целей, например, для генерации карты сайта для всех языков
    public static function setCustomLang($lang)
    {
        self::$custom_lang = $lang;
    }

и переменную
    public static $custom_lang = '';

также, в методе afterFetch заменить

    self::setLang(LANG); // Устанавливаем текущий язык
на
    // Если есть массив переводов и установлена константа активного языка
    if(self::$custom_lang){
        self::setLang(self::$custom_lang);
    } else {
        self::setLang(LANG); // Устанавливаем текущий язык
    }


 */