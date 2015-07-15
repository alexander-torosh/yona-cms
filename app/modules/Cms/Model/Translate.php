<?php
/**
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.net)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Cms\Model;

use Application\Mvc\Helper\CmsCache;
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

    public static function translates()
    {
        return CmsCache::getInstance()->get('translates');
    }

    public static function findCachedByLangInArray($lang = null)
    {
        $translates = self::translates();
        return $translates[$lang];
    }

    public function findByPhraseAndLang($phrase, $lang = null)
    {
        if (!$lang) {
            $lang = LANG;
        }
        $result = self::findFirst([
            'phrase = :phrase: AND lang = :lang:',
            'bind' => [
                'phrase' => $phrase,
                'lang'   => $lang,
            ]
        ]);
        return $result;
    }

    public static function buildCmsTranslatesCache()
    {
        $save = [];
        $languages = Language::find();
        foreach($languages as $lang) {
            $save[$lang->getIso()] = [""=>""];
        }

        $entries = Translate::find();
        foreach ($entries as $el) {
            $save[$el->getLang()][$el->getPhrase()] = $el->getTranslation();
        }
        return $save;
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