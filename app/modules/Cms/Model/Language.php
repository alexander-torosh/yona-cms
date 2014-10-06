<?php
/**
 * @copyright Copyright (c) 2011 - 2014 Oleksandr Torosh (http://wezoom.net)
 * @author Oleksandr Torosh <web@wezoom.net>
 */

namespace Cms\Model;

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
    public $name;
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
                "message" => "Одинаковые ISO для языков не допускаются"
            )
        ));
        $this->validate(new PresenceOf(array(
            'field' => 'iso',
            'message' => 'Укажите ISO'
        )));

        /**
         * Name
         */
        $this->validate(new Uniqueness(
            array(
                "field" => "name",
                "message" => "Одинаковые имена для языков не допускаются"
            )
        ));
        $this->validate(new PresenceOf(array(
            'field' => 'name',
            'message' => 'Укажите имя'
        )));

        /**
         * URL
         */
        $this->validate(new Uniqueness(
            array(
                "field" => "url",
                "message" => "Одинаковые URL для языков не допускаются"
            )
        ));


        if ($this->primary == 0) {
            $this->validate(new PresenceOf(array(
                'field' => 'url',
                'message' => 'Укажите URL'
            )));
        }

        return $this->validationHasFailed() != true;
    }

    public function afterCreate()
    {
        $this->sortorder = $this->getUpperSortorder() + 1;

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
        return self::find(array(
            'order' => 'primary DESC, sortorder ASC',
            'cache' => array(
                'key' => self::cacheKey(),
                'lifetime' => 300,
            ),
        ));
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
        if ($primary === 1) {
            $languages = self::find();
            foreach ($languages as $lang) {
                if ($lang->getId() != $this->id) {
                    $lang->primary = 0;
                    $lang->update();
                }
            }
            $this->primary = 1;
        } else {
            $this->primary = 0;
        }
    }

    /**
     * @return mixed
     */
    public function getPrimary()
    {
        return $this->primary;
    }


} 