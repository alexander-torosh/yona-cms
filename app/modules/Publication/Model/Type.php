<?php
 /**
 * @copyright Copyright (c) 2011 - 2014 Oleksandr Torosh (http://wezoom.net)
 * @author Oleksandr Torosh <web@wezoom.net>
 */

namespace Publication\Model;

use Application\Mvc\Model;

class Type extends Model
{

    public function getSource()
    {
        return "publication_type";
    }

    protected $translateModel = 'Publication\Model\Translate\TypeTranslate';

    public $id;
    public $title; // translate
    public $slug;
    public $limit = 10;
    public $format = 'list';
    public $head_title; // translate
    public $meta_description; // translate
    public $meta_keywords; // translate
    public $seo_text; // translate

    public static $formats = array(
        'list' => 'Список',
        'grid' => 'Сетка',
    );

    public function initialize()
    {
        $this->hasMany("id", $this->translateModel, "foreign_id"); // translate
    }

    public static function getCachedBySlug($slug)
    {
        $result = self::findFirst(array(
            'slug = :slug:',
            'bind' => array(
                'slug' => $slug,
            ),
            'cache' => array(
                'key' => self::cacheSlugKey($slug),
                'lifetime' => 60,
            ),
        ));
        return $result;
    }

    public static function cacheSlugKey($slug)
    {
        $key = HOST_HASH . md5('Publication\Model\Type::' . $slug);
        return $key;
    }

    /**
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param mixed $head_title
     */
    public function setHeadTitle($head_title)
    {
        $this->setMLVariable('head_title', $head_title);
    }

    /**
     * @return mixed
     */
    public function getHeadTitle()
    {
        return $this->getMLVariable('head_title');
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
     * @param mixed $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @return mixed
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param mixed $meta_description
     */
    public function setMetaDescription($meta_description)
    {
        $this->setMLVariable('meta_description', $meta_description);
    }

    /**
     * @return mixed
     */
    public function getMetaDescription()
    {
        return $this->getMLVariable('meta_description');
    }

    /**
     * @param mixed $meta_keywords
     */
    public function setMetaKeywords($meta_keywords)
    {
        $this->setMLVariable('meta_keywords', $meta_keywords);
    }

    /**
     * @return mixed
     */
    public function getMetaKeywords()
    {
        return $this->getMLVariable('meta_keywords');
    }

    /**
     * @param mixed $seo_text
     */
    public function setSeoText($seo_text)
    {
        $this->setMLVariable('seo_text', $seo_text);
    }

    /**
     * @return mixed
     */
    public function getSeoText()
    {
        return $this->getMLVariable('seo_text');
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    

} 