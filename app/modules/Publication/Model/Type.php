<?php
/**
 * @copyright Copyright (c) 2011 - 2014 Oleksandr Torosh (http://wezoom.net)
 * @author Oleksandr Torosh <web@wezoom.net>
 */

namespace Publication\Model;

use Application\Mvc\Helper\CmsCache;
use Application\Mvc\Model\Model;
use Phalcon\DI;
use Phalcon\Mvc\Model\Validator\Uniqueness;

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
    public $display_date;
    public $format = 'list';
    public $head_title; // translate
    public $meta_description; // translate
    public $meta_keywords; // translate
    public $seo_text; // translate

    public static $formats = [
        'list' => 'List',
        'grid' => 'Grid',
    ];

    public function initialize()
    {
        $this->hasMany('id', $this->translateModel, 'foreign_id'); // translate

        $this->hasMany('id', 'Publication\Model\Publication', 'type_id', [
            'alias' => 'publications'
        ]);
    }

    public function validation()
    {
        $this->validate(new Uniqueness(
            [
                "field"   => "slug",
                "message" => "Тип публикаций с таким URL раздела = '" . $this->slug . "' существует"
            ]
        ));

        return $this->validationHasFailed() != true;
    }

    public function afterUpdate()
    {
        parent::afterUpdate();

        $cache = $this->getDi()->get('cache');
        $cache->delete(self::cacheSlugKey($this->getSlug()));
    }

    public function afterSave()
    {
        CmsCache::getInstance()->save('publication_types', $this->buildCmsTypesCache());
    }

    public function afterDelete()
    {
        CmsCache::getInstance()->save('publication_types', $this->buildCmsTypesCache());
    }

    private function buildCmsTypesCache()
    {
        $types = self::find();
        $save = [];
        foreach ($types as $type) {
            $save[$type->getSlug()] = [
                'id' => $type->getId(),
                'slug' => $type->getSlug(),
            ];
        }
        return $save;
    }

    public function updateFields($data)
    {
        if (!$this->getSlug()) {
            $this->setSlug(Transliterator::slugify($data['title']));
        }
        if (!$this->getTitle()) {
            $this->setTitle($data['title']);
        }
        if (!$this->getHead_title()) {
            $this->setHead_title($data['title']);
        }
        if (isset($data['display_date'])) {
            $this->setDisplay_date(1);
        } else {
            $this->setDisplay_date(0);
        }
    }

    public static function types()
    {
        return CmsCache::getInstance()->get('publication_types');
    }

    public static function cachedListArray($params = [])
    {
        $cache = DI::getDefault()->get('cache');
        $key = self::cacheListKey($params);
        $list = $cache->get($key);
        if (!$list) {
            $result = self::find();
            $list = [];
            foreach ($result as $el) {
                if (isset($params['value']) && $params['value']) {
                    $value = $el->{$params['value']};
                } else {
                    $value = $el->getTitle();
                }
                if (isset($params['key']) && $params['key']) {
                    $list[$el->{$params['key']}] = $value;
                } else {
                    $list[$el->getSlug()] = $value;
                }
            }
            $cache->save($key, $list, 120);
        }

        return $list;
    }

    public static function getCachedBySlug($slug)
    {
        $data = self::findFirst([
            'slug = :slug:',
            'bind' => [
                'slug' => $slug,
            ],
            'cache' => [
                'key' => self::cacheSlugKey($slug),
                'lifetime' => 86400,
            ]
        ]);

        return $data;
    }

    public static function cacheSlugKey($slug)
    {
        return HOST_HASH . md5('Publication\Model\Type; slug = ' . $slug);
    }

    public static function cacheListKey($params)
    {
        return HOST_HASH . md5('Publication\Model\Type; list; ' . serialize($params));
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

    public function getFormatTitle()
    {
        if (array_key_exists($this->format, self::$formats)) {
            return self::$formats[$this->format];
        }
    }

    /**
     * @param mixed $head_title
     */
    public function setHead_title($head_title)
    {
        $this->setMLVariable('head_title', $head_title);
    }

    /**
     * @return mixed
     */
    public function getHead_title()
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
    public function setMeta_description($meta_description)
    {
        $this->setMLVariable('meta_description', $meta_description);
    }

    /**
     * @return mixed
     */
    public function getMeta_description()
    {
        return $this->getMLVariable('meta_description');
    }

    /**
     * @param mixed $meta_keywords
     */
    public function setMeta_keywords($meta_keywords)
    {
        $this->setMLVariable('meta_keywords', $meta_keywords);
    }

    /**
     * @return mixed
     */
    public function getMeta_keywords()
    {
        return $this->getMLVariable('meta_keywords');
    }

    /**
     * @param mixed $seo_text
     */
    public function setSeo_text($seo_text)
    {
        $this->setMLVariable('seo_text', $seo_text);
    }

    /**
     * @return mixed
     */
    public function getSeo_text()
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

    /**
     * @param mixed $display_date
     */
    public function setDisplay_date($display_date)
    {
        $this->display_date = $display_date;
    }

    /**
     * @return mixed
     */
    public function getDisplay_date()
    {
        return $this->display_date;
    }


} 