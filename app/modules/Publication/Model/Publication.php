<?php

namespace Publication\Model;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Validator\Uniqueness;
use Phalcon\Mvc\Model\Validator\PresenceOf;
use Application\Localization\Transliterator;

class Publication extends Model
{

    public function getSource()
    {
        return "publication";
    }

    public $id;
    public $type;
    public $title;
    public $slug;
    public $text;
    public $meta_title;
    public $meta_description;
    public $meta_keywords;
    public $created_at;
    public $updated_at;
    public $date;
    public $preview_inner;

    public static $types = array(
        'news' => 'Новости',
        'articles' => 'Статьи',
    );

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
        if ($_POST['form']) {
            $this->preview_inner = (isset($_POST['preview_inner'])) ? 1 : 0;
        }
    }

    public function validation()
    {
        $this->validate(new Uniqueness(
            array(
                "field" => "slug",
                "message" => "Страница с такой транслитерацией уже существует"
            )
        ));

        $this->validate(new PresenceOf(array(
            'field' => 'title',
            'message' => 'Укажите название страницы'
        )));


        return $this->validationHasFailed() != true;
    }

    public function afterValidation()
    {
        if (!$this->meta_title) {
            $this->setMetaTitle($this->title);
        }
        if (!$this->slug) {
            $this->setSlug(Transliterator::slugify($this->title));
        }
        if (!$this->date) {
            $this->date = date("Y-m-d H:i:s");
        }
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
        $this->meta_description = $meta_description;
    }

    /**
     * @return mixed
     */
    public function getMetaDescription()
    {
        return $this->meta_description;
    }

    /**
     * @param mixed $meta_keywords
     */
    public function setMetaKeywords($meta_keywords)
    {
        $this->meta_keywords = $meta_keywords;
    }

    /**
     * @return mixed
     */
    public function getMetaKeywords()
    {
        return $this->meta_keywords;
    }

    /**
     * @param mixed $meta_title
     */
    public function setMetaTitle($meta_title)
    {
        $this->meta_title = $meta_title;
    }

    /**
     * @return mixed
     */
    public function getMetaTitle()
    {
        return $this->meta_title;
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
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
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

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getDate($format = 'Y-m-d')
    {
        if ($format) {
            return date($format, strtotime($this->date));
        } else {
            return $this->date;
        }
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    public function getTypeTitle()
    {
        if ($this->type) {
            if (array_key_exists($this->type, self::$types)) {
                return self::$types[$this->type];
            }
        }
    }

    /**
     * @param mixed $preview_inner
     */
    public function setPreviewInner($preview_inner)
    {
        $this->preview_inner = $preview_inner;
    }

    /**
     * @return mixed
     */
    public function getPreviewInner()
    {
        return $this->preview_inner;
    }

    public static function findCachedBySlug($slug)
    {
        $query = "slug = '$slug'";
        $key = HOST_HASH . md5("Publication::findFirst($query)");
        $publication = self::findFirst(array($query, 'cache' => array('key' => $key, 'lifetime' => 60)));
        return $publication;
    }

}