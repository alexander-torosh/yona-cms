<?php

/**
 * Model
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */
namespace Application\Mvc;

class Model extends \Phalcon\Mvc\Model
{

    private static $lang = 'ru';

    public static function setLang($lang)
    {
        self::$lang = $lang;
    }

    public function getLangSuffix($lang)
    {
        return ($lang == 'ru') ? '' : '_uk' ;
    }

    public function getMLVariable($variable)
    {
        return $this->{$variable . $this->getLangSuffix(self::$lang)};
    }

    public function setMLVariable($variable, $lang = 'ru')
    {
        return $this->{$variable . $this->getLangSuffix($lang)};
    }

}
