<?php
/**
 * @copyright Copyright (c) 2011 - 2014 Oleksandr Torosh (http://wezoom.net)
 * @author Oleksandr Torosh <web@wezoom.net>
 */

namespace Cms\Model;

use Application\Mvc\Helper\CmsCache;
use Phalcon\DI;
use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\PresenceOf;

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
        $validator = new Validation();

        /**
        * ISO
        */
        $validator->add('iso', new Uniqueness([
            'model' => $this,
            "message" => "The inputted ISO language is existing"
        ]));
        $validator->add('iso', new PresenceOf([
            'model' => $this,
            'message' => 'ISO is required'
        ]));

        /**
        * Name
        */
        $validator->add('name', new Uniqueness([
            'model' => $this,
            "message" => "The inputted name is existing"
        ]));
        $validator->add('name', new PresenceOf([
            'model' => $this,
            'message' => 'Name is required'
        ]));

        /**
        * URL
        */
        $validator->add('url', new Uniqueness([
            'model' => $this,
            "message" => "The inputted URL is existing"
        ]
    ));

    if ($this->primary == 0) {
        $validator->add('url', new PresenceOf([
            'model' => $this,
            'message' => 'URL is required'
        ]));
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

    public function afterSave()
    {
        CmsCache::getInstance()->save('languages', $this->buildCmsLanguagesCache());
        CmsCache::getInstance()->save('translates', Translate::buildCmsTranslatesCache());
    }

    public function afterDelete()
    {
        CmsCache::getInstance()->save('languages', $this->buildCmsLanguagesCache());
        CmsCache::getInstance()->save('translates', Translate::buildCmsTranslatesCache());
    }

    private function buildCmsLanguagesCache()
    {
        $modelsManager = DI::getDefault()->get('modelsManager');
        $qb = $modelsManager->createBuilder();
        $qb->from('Cms\Model\Language');
        $qb->orderBy('primary DESC, sortorder ASC');

        $entries = $qb->getQuery()->execute();
        $save = [];
        if ($entries->count()) {
            foreach ($entries as $el) {
                $save[$el->getIso()] = [
                    'id'         => $el->getId(),
                    'iso'        => $el->getIso(),
                    'locale'     => $el->getLocale(),
                    'name'       => $el->getName(),
                    'short_name' => $el->getShort_name(),
                    'url'        => $el->getUrl(),
                    'primary'    => $el->getPrimary(),
                ];
            }
        }
        return $save;
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
        return CmsCache::getInstance()->get('languages');
    }

    public static function findCachedLanguagesIso()
    {
        $languages = self::findCachedLanguages();
        $iso_array = [];
        if (!empty($languages)) {
            foreach ($languages as $lang) {
                $iso_array[] = $lang['iso'];
            }
        }
        return $iso_array;
    }

    public static function findCachedByIso($iso)
    {
        $languages = self::findCachedLanguages();
        foreach ($languages as $lang) {
            if ($iso == $lang['iso']) {
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
            foreach ($languages as $lang) {
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
