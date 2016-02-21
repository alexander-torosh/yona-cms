<?php

namespace Publication\Model;

use Application\Mvc\Model\Model;
use Phalcon\Mvc\Model\Validator\Uniqueness;
use Application\Localization\Transliterator;
use Yona\Cache\Keys;

class Publication extends Model
{

    public function getSource()
    {
        return "publication";
    }

    protected $id;
    protected $type_id;
    protected $slug;
    protected $date;

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

    protected $preview_src;
    protected $preview_inner;
    protected $created_at;
    protected $updated_at;

    public function initialize()
    {
        $this->belongsTo('type_id', 'Publication\Model\Type', 'id', [
            'alias' => 'Type'
        ]);
    }

    public function beforeCreate()
    {
        $this->created_at = date("Y-m-d H:i:s");
    }

    public function beforeUpdate()
    {
        $this->updated_at = date("Y-m-d H:i:s");
    }

    public function afterUpdate()
    {
        $this->getDi()->get('cacheManager')->delete([
            Keys::PUBLICATION_BY_SLUG,
            $this->getSlug()
        ]);
    }

    public function validation()
    {
        $this->validate(new Uniqueness([
            "field"   => "slug",
            "message" => "Page with slug = '" . $this->slug . "' already exists"
        ]));

        return $this->validationHasFailed() != true;
    }

    public function afterValidation()
    {
        if (!$this->date) {
            $this->date = date("Y-m-d H:i:s");
        }
        if (!$this->getSlug()) {
            $this->setSlug(Transliterator::slugify($this->getTitle()));
        } else {
            $this->setSlug(Transliterator::slugify($this->getSlug()));
        }
        if (!$this->getHeadTitle()) {
            $this->setHeadTitle($this->getTitle());
        }
    }

    public function updateFields($data)
    {
        $this->setPreviewInner(isset($data['preview_inner']) ? 1 : 0);
    }

    public function setId($id)
    {
        $this->id = $id;
    }

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

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getDate($format = 'Y-m-d H:i:s')
    {
        if ($format) {
            if ($this->date) {
                return date($format, strtotime($this->date));
            }
        } else {
            return $this->date;
        }
    }

    public function setTypeId($type_id)
    {
        $this->type_id = $type_id;
    }

    public function getTypeId()
    {
        return $this->type_id;
    }

    public function getTypeTitle()
    {
        if ($this->type_id) {
            $types = Type::cachedListArray(['key' => 'id']);
            if (array_key_exists($this->type_id, $types)) {
                return $types[$this->type_id];
            }
        }
    }

    public function getTypeSlug()
    {
        if ($this->type_id) {
            $types = Type::cachedListArray(['key' => 'id', 'value' => 'slug']);
            if (array_key_exists($this->type_id, $types)) {
                return $types[$this->type_id];
            }
        }
    }

    public function setPreviewInner($preview_inner)
    {
        $this->preview_inner = $preview_inner;
    }

    public function getPreviewInner()
    {
        return $this->preview_inner;
    }

    public function getPreviewSrc()
    {
        return $this->preview_src;
    }

    public function setPreviewSrc($preview_src)
    {
        $this->preview_src = $preview_src;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

}