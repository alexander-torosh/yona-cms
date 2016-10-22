<?php

namespace Page\Model;

use Application\Mvc\Model\Model;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness as UniquenessValidator;
use Application\Localization\Transliterator;

class Page extends Model
{

    public function getSource()
    {
        return "page";
    }

    protected $translateModel = 'Page\Model\Translate\PageTranslate'; // translate

    public $id;
    public $slug;
    public $title; // translate
    public $text; // translate
    public $meta_title; // translate
    public $meta_description; // translate
    public $meta_keywords; // translate
    public $created_at;
    public $updated_at;

    public function initialize()
    {
        $this->hasMany("id", $this->translateModel, "foreign_id"); // translate
    }

    public function beforeCreate()
    {
        $this->created_at = date("Y-m-d H:i:s");
    }

    public function beforeUpdate()
    {
        $this->updated_at = date("Y-m-d H:i:s");
    }

    public function updateFields($data)
    {
        if (!$this->getSlug()) {
            $this->setSlug(Transliterator::slugify($data['title']));
        }
        if (!$this->getMetaTitle()) {
            $this->setMetaTitle($data['title']);
        }
    }

    public function validation()
    {
      $validator = new Validation();
      $validator->add('slug', new UniquenessValidator(
          [
              "model"   => $this,
              "message" => $this->getDi()->get('helper')->translate("Page with slug is already exists")
          ]
      ));
      return $this->validate($validator);
    }

    public static function findCachedBySlug($slug)
    {
        $queryML = "key = 'slug' and value = '$slug'";
        $key = HOST_HASH . md5("Page::findFirst($queryML)");
        $slugRecord = Translate\PageTranslate::findFirst([$queryML, 'cache' => array('key' => $key, 'lifetime' => 60)]);

        $key = HOST_HASH . md5("Page::findFirst($query)");
        $page = self::findFirst([
          'conditions' => 'id = :pageId:',
          'bind' => ['pageId' => $slugRecord->foreign_id],
          'cache' => array('key' => $key, 'lifetime' => 60)
          ]);
          var_dump($pages);
        return $page;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
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
        return $this;
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
        return $this;
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
        return $this;
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
    public function setMetaTitle($meta_title)
    {
        $this->setMLVariable('meta_title', $meta_title);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetaTitle()
    {
        return $this->getMLVariable('meta_title');
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->setMLVariable('slug', $slug);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->getMLVariable('slug');
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->setMLVariable('text', $text);
        return $this;
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
        return $this;
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
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

}
