<?php

/**
 * Model
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */
namespace Application\Mvc\Model;

class Model extends \Phalcon\Mvc\Model
{
    public static $lang = 'en';

    public function afterFetch()
    {
        if (defined('LANG')) {
            self::setLang(LANG);
        }
    }

    public static function setLang($lang)
    {
        self::$lang = $lang;
    }

    public static function getLang()
    {
        return self::$lang;
    }

    public function getLangSuffix($lang = null)
    {
        if ($lang) {
            $searchLang = $lang;
        } else {
            $searchLang = LANG;
        }
        if ($searchLang != self::$lang) {
            return '_' . $searchLang;
        } else {
            return '';
        }
    }

    public function getMLVariable($variable, $lang = null)
    {
        return $this->{$variable . $this->getLangSuffix($lang)};
    }

    public function setMLVariable($variable, $value, $lang = null)
    {
        $this->{$variable . $this->getLangSuffix($lang)} = $value;
    }

}
