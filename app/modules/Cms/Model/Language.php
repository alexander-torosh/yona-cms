<?php
/**
 * @copyright Copyright (c) 2011 - 2014 Oleksandr Torosh (http://wezoom.net)
 * @author Oleksandr Torosh <web@wezoom.net>
 */

namespace Cms\Model;

use Phalcon\DI;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Validator\Uniqueness;
use Phalcon\Mvc\Model\Validator\PresenceOf;

class Language extends Model
{

    public function getSource()
    {
        return "language";
    }

    public $id;
    public $iso;
    public $locale;
    public $name;
    public $short_name;
    public $url;
    public $sortorder;
    public $primary;

    public function validation()
    {
        /**
         * ISO
         */
        $this->validate(new Uniqueness(
            array(
                "field" => "iso",
                "message" => "The inputted ISO language is existing"
            )
        ));
        $this->validate(new PresenceOf(array(
            'field' => 'iso',
            'message' => 'ISO is required'
        )));

        /**
         * Name
         */
        $this->validate(new Uniqueness(
            array(
                "field" => "name",
                "message" => "The inputted name is existing"
            )
        ));
        $this->validate(new PresenceOf(array(
            'field' => 'name',
            'message' => 'Name is required'
        )));

        /**
         * URL
         */
        $this->validate(new Uniqueness(
            array(
                "field" => "url",
                "message" => "The inputted URL is existing"
            )
        ));


        if ($this->primary == 0) {
            $this->validate(new PresenceOf(array(
                'field' => 'url',
                'message' => 'URL is required'
            )));
        }

        return $this->validationHasFailed() != true;
    }

    public function afterCreate()
    {
        $this->sortorder = $this->getUpperSortorder() + 1;

    }

    public function afterUpdate()
    {
        $cache = $this->getDI()->get('cache');
        $cache->delete(self::cacheKey());
    }

    public function afterValidation()
    {
        if (!$this->sortorder) {
            $this->sortorder = $this->getUpperSortorder();
        }

    }

    public function afterValidationOnCreate()
    {
        $this->sortorder = $this->getUpperSortorder() + 1;

    }

    public static function findCachedLanguages()
    {
        $cache = DI::getDefault()->get('cache');
        $data = $cache->get(self::cacheKey());
        if (!$data) {
            $data = Language::find(array(
                'order' => 'primary DESC, sortorder ASC',
            ));
            if ($data) {
                $cache->save(self::cacheKey(), $data, 300);
            }
        }
        return $data;

    }

    public static function findCachedLanguagesIso()
    {
        $languages = self::findCachedLanguages();
        $iso_array = array();
        if (!empty($languages)) {
            foreach ($languages as $lang) {
                $iso_array[] = $lang->getIso();
            }
        }
        return $iso_array;
    }

    public static function findCachedByIso($iso)
    {
        $languages = self::findCachedLanguages();
        foreach ($languages as $lang) {
            if ($iso == $lang->getIso()) {
                return $lang;
            }
        }
    }

    public static function cacheKey()
    {
        return HOST_HASH . md5('Language::findCachedLanguages');
    }

    public function getUpperSortorder()
    {
        $count = self::count();
        return $count;

    }

    public function setOnlyOnePrimary()
    {
        if ($this->getPrimary() == 1) {
            $languages = $this->find();
            foreach($languages as $lang) {
                if ($lang->getId() != $this->getId()) {
                    $lang->setPrimary(0);
                    $lang->save();
                }
            }
        } else {
            $primary = $this->findFirst("primary = '1'");
            if (!$primary) {
                $this->setPrimary(1);
                $this->save();
                $this->getDI()->get('flash')->notice('There should always be a primary language');
            }
        }
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
     * @param mixed $iso
     */
    public function setIso($iso)
    {
        $this->iso = $iso;
    }

    /**
     * @return mixed
     */
    public function getIso()
    {
        return $this->iso;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $sortorder
     */
    public function setSortorder($sortorder)
    {
        $this->sortorder = $sortorder;
    }

    /**
     * @return mixed
     */
    public function getSortorder()
    {
        return $this->sortorder;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $primary
     */
    public function setPrimary($primary)
    {
        $this->primary = $primary;
    }

    /**
     * @return mixed
     */
    public function getPrimary()
    {
        return $this->primary;
    }

    /**
     * @param mixed $short_name
     */
    public function setShort_name($short_name)
    {
        $this->short_name = $short_name;
    }

    /**
     * @return mixed
     */
    public function getShort_name()
    {
        return $this->short_name;
    }

    /**
     * @param mixed $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

}