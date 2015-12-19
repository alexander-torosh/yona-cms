<?php

namespace Page\Model;

use Application\Mvc\Model\Model;
use Phalcon\Mvc\Model\Validator\Uniqueness;
use Phalcon\Mvc\Model\Validator\PresenceOf;
use Application\Localization\Transliterator;

class Page extends Model
{

    public function getSource()
    {
        return "page";
    }

    public $id;
    public $slug;
    public $title;
    public $head_title;
    public $meta_description;
    public $meta_keywords;
    public $text;
    public $created_at;
    public $updated_at;

    public function initialize()
    {

    }

    public function beforeCreate()
    {
        $this->created_at = date("Y-m-d H:i:s");
    }

    public function beforeUpdate()
    {
        $this->updated_at = date("Y-m-d H:i:s");
    }

    public function beforeValidation()
    {
        if (!$this->getSlug()) {
            $this->setSlug(Transliterator::slugify($this->getTitle()));
        } else {
            $this->setSlug(Transliterator::slugify($this->getSlug()));
        }
        if (!$this->getHeadTitle()) {
            $this->setHeadTitle($this->getTitle());
        }
    }

    public function validation()
    {
        $this->validate(new Uniqueness(
            array(
                "field" => "slug",
                "message" => "Page with slug is already exists"
            )
        ));

        return $this->validationHasFailed() != true;
    }

    public static function findCachedBySlug($slug)
    {
        $query = "slug = '$slug'";
        $key = HOST_HASH . md5("Page::findFirst($query)");
        $page = self::findFirst(array($query, 'cache' => array('key' => $key, 'lifetime' => 60)));
        return $page;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
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
     * @param mixed $meta_title
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

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->setMLVariable('text', $text);
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->getMLVariable('text');
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->setMLVariable('title', $title);
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->getMLVariable('title');
    }

    /**
     * @param mixed $updated_at
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

}
