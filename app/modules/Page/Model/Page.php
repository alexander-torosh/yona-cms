<?php

namespace Page\Model;

use Application\Mvc\Model\Model;
use Phalcon\Di;
use Phalcon\Mvc\Model\Validator\Uniqueness;
use Phalcon\Mvc\Model\Validator\PresenceOf;
use Application\Localization\Transliterator;
use Yona\Cache\Keys;

class Page extends Model
{
    const CACHE_SLUG_KEY = 'Page by slug';

    public function getSource()
    {
        return "page";
    }

    protected $id;
    protected $slug;

    protected $title;
    protected $title_uk;
    protected $title_ru;
    protected $head_title;
    protected $head_title_uk;
    protected $head_title_ru;
    protected $meta_description;
    protected $meta_description_uk;
    protected $meta_description_ru;
    protected $meta_keywords;
    protected $meta_keywords_uk;
    protected $meta_keywords_ru;
    protected $text;
    protected $text_uk;
    protected $text_ru;

    protected $created_at;
    protected $updated_at;

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
        $this->validate(new Uniqueness([
            "field"   => "slug",
            "message" => "Page with slug is already exists"
        ]));

        return $this->validationHasFailed() != true;
    }

    public function afterUpdate()
    {
        $this->getDi()->get('cacheManager')->delete([
            Keys::PAGE_BY_SLUG,
            $this->getSlug()
        ]);
    }

    public static function findCachedBySlug($slug)
    {
        $cacheManager = Di::getDefault()->get('cacheManager');
        return $cacheManager->load([
            Keys::PAGE_BY_SLUG,
            $slug
        ], function () use ($slug) {
            return self::findFirstBySlug($slug);
        }, 300);
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
