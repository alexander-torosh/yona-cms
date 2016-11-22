<?php

namespace Publication\Model;

use Application\Cache\Keys;
use Application\Mvc\Model\Model;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness as UniquenessValidator;
use Application\Localization\Transliterator;

class Publication extends Model
{

    public function getSource()
    {
        return "publication";
    }

    public function initialize()
    {
        $this->hasMany('id', $this->translateModel, 'foreign_id'); // translate

        $this->belongsTo('type_id', 'Publication\Model\Type', 'id', [
            'alias' => 'type'
        ]);
    }

    private $id;
    private $type_id;
    private $slug;
    private $created_at;
    private $updated_at;
    private $date;
    private $preview_src;
    private $preview_inner;
    
    protected $title;
    protected $text;
    protected $meta_title;
    protected $meta_description;
    protected $meta_keywords;

    protected $translateModel = 'Publication\Model\Translate\PublicationTranslate'; // translate
    protected $translateFields = [
        'title',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'text'
    ];

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
        parent::afterUpdate();

        $cache = $this->getDi()->get('cache');

        $cache->delete(self::cacheSlugKey($this->getSlug()));

        $this->cacheManager->delete([
            Keys::PUBLICATION,
            $this->slug,
            self::$lang
        ]);
    }

    public function validation()
    {
        $validator = new Validation();
        $validator->add('slug', new UniquenessValidator(
            [
                "model"   => $this,
                "message" => $this->getDi()->get('helper')->translate("Publishcation with slug is already exists")
            ]
        ));
        return $this->validate($validator);
    }

    public function afterValidation()
    {
        if (!$this->date) {
            $this->date = date("Y-m-d H:i:s");
        }
    }

    public function updateFields($data)
    {
        if (!$this->getSlug()) {
            $this->setSlug(Transliterator::slugify($data['title']));
        }
        if (!$this->getMetaTitle()) {
            $this->setMetaTitle($data['title']);
        }
        $this->setPreviewInner(isset($data['preview_inner']) ? 1 : 0);
    }

    public static function findCachedBySlug($slug)
    {
        $publication = self::findFirst(["slug = '$slug'",
            'cache' => [
                'key'      => self::cacheSlugKey($slug),
                'lifetime' => 60]
        ]);
        return $publication;
    }

    public static function cacheSlugKey($slug)
    {
        $key = HOST_HASH . md5('Publication\Model\Publication; slug = ' . $slug);
        return $key;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setMetaDescription($meta_description)
    {
        $this->setMLVariable('meta_description', $meta_description);
        return $this;
    }

    public function getMetaDescription()
    {
        return $this->getMLVariable('meta_description');
    }

    public function setMetaKeywords($meta_keywords)
    {
        $this->setMLVariable('meta_keywords', $meta_keywords);
        return $this;
    }

    public function getMetaKeywords()
    {
        return $this->getMLVariable('meta_keywords');
    }

    public function setMetaTitle($meta_title)
    {
        $this->setMLVariable('meta_title', $meta_title);
        return $this;
    }

    public function getMetaTitle()
    {
        return $this->getMLVariable('meta_title');
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setText($text)
    {
        $this->setMLVariable('text', $text);
        return $this;
    }

    public function getText()
    {
        return $this->getMLVariable('text');
    }

    public function setTitle($title)
    {
        $this->setMLVariable('title', $title);
        return $this;
    }

    public function getTitle()
    {
        return $this->getMLVariable('title');
    }

    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function setDate($date)
    {
        $this->date = $date;
        return $this;
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
        return $this;
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

    public function getTypeDisplayDate()
    {
        if ($this->type_id) {
            $types = Type::cachedListArray(['key' => 'id', 'value' => 'display_date']);
            if (array_key_exists($this->type_id, $types)) {
                return $types[$this->type_id];
            }
        }
    }

    public function setPreviewInner($preview_inner)
    {
        $this->preview_inner = $preview_inner;
        return $this;
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
        return $this;
    }

}
