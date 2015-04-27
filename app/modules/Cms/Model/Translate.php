<?php
/**
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.net)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Cms\Model;

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
        $key = time() . HOST_HASH . md5("Translate::findByLang($lang)");
        $result = self::find(array(
            'lang = :lang:',
            'bind' => array(
                'lang' => $lang,
            ),
            'cache' => array(
                'key' => $key,
                'lifetime' => 300,
            )
        ));
        $translations = array();
        if ($result) {
            foreach($result as $el) {
                $translations[$el->getPhrase()] = $el->getTranslation();
            }
        }
        return $translations;
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