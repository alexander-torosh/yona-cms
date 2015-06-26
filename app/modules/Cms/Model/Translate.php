<?php
/**
     * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.net)
     * @author Aleksandr Torosh <webtorua@gmail.com>
     */

namespace Cms\Model;

use Phalcon\DI;
use Phalcon\Mvc\Model;

class Translate extends Model
{

    public function getSource()
    {
        return "translate";
    }

    public $id;
    public $lang;
    public $phrase;
    public $translation;

    public static function findCachedByLangInArray($lang = null)
    {
        if (!$lang) {
            $lang = LANG;
        }
        $cache = DI::getDefault()->get('cache');
        $data = $cache->get(self::cacheKey($lang));
        if (!$data) {
            $data = self::find(array(
                'lang = :lang:',
                'bind' => array(
                    'lang' => $lang,
                ),
            ));
            if ($data) {
                $cache->save(self::cacheKey($lang), $data, 300);
            }
        }

        $translations = array();
        if ($data) {
            foreach ($data as $el) {
                $translations[$el->getPhrase()] = $el->getTranslation();
            }
        }
        return $translations;
    }

    public function afterUpdate()
    {
        $cache = $this->getDI()->get('cache');
        $cache->delete(self::cacheKey(LANG));
    }

    public static function cacheKey($lang)
    {
        return HOST_HASH . md5("Translate::findByLang($lang)"); ;
    }

    public function findByPhraseAndLang($phrase, $lang = null)
    {
        if (!$lang) {
            $lang = LANG;
        }
        $result = self::findFirst(array(
            'phrase = :phrase: AND lang = :lang:',
            'bind' => array(
                'phrase' => $phrase,
                'lang' => $lang,
            )
        ));
        return $result;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $lang
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
    }

    /**
     * @return mixed
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @param mixed $phrase
     */
    public function setPhrase($phrase)
    {
        $this->phrase = $phrase;
    }

    /**
     * @return mixed
     */
    public function getPhrase()
    {
        return $this->phrase;
    }

    /**
     * @param mixed $translation
     */
    public function setTranslation($translation)
    {
        $this->translation = $translation;
    }

    /**
     * @return mixed
     */
    public function getTranslation()
    {
        return $this->translation;
    }


}