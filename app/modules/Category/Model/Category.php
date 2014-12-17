<?php

/**
 * Category
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Category\Model;

use Application\Localization\Transliterator;
use Phalcon\Mvc\Model\Message;

class Category extends \Application\Mvc\Model
{

    public function getSource()
    {
        return "category";

    }

    public function afterFetch()
    {
        self::setLang(LANG);
    }

    public function initialize()
    {
        $this->belongsTo("parent_id", "Category\Model\Category", "id", array(
            'alias' => 'ParentCategory'
        ));

        $this->hasManyToMany(
            "id", "Publication\Model\PublicationCategories", "category_id", "publication_id", "Publication\Model\Publication", "id", array(
                'alias' => 'publications'
            )
        );

        $this->keepSnapshots(true);

    }

    public static $types = array(
        'catalog' => 'Каталог',
        'publication' => 'Публикации',
    );
    public $id;
    public $type = 'catalog';
    public $parent_id = null;
    public $sortorder;
    public $title;
    public $title_uk;
    public $slug;
    public $meta_title;
    public $meta_title_uk;
    public $meta_description;
    public $meta_description_uk;
    public $meta_keywords;
    public $meta_keywords_uk;
    public $text;
    public $text_uk;
    public $visible;

    public function afterCreate()
    {
        $this->sortorder = $this->getUpperSortorder() + 1;

    }

    public function beforeValidation()
    {
        if (isset($_POST['category_form']) && $_POST['category_form']) {
            $this->visible = (isset($_POST['visible']) && $_POST['visible']) ? 1 : 0;
        }
    }

    public function beforeValidationOnUpdate()
    {
        if ($this->id == $this->parent_id) {
            $this->appendMessage(new Message(
                "Категория не может быть родителем самой себя"
            ));
            return false;
        }

        return $this->validationHasFailed() != true;

    }

    public function validation()
    {
        return $this->validationHasFailed() != true;

    }

    public function afterValidation()
    {
        if (!$this->meta_title) {
            $this->setMeta_title($this->title);
        }
        if (!$this->meta_title_uk) {
            $this->setMeta_title_uk($this->title_uk);
        }
        if (!$this->slug) {
            $this->setSlug(Transliterator::slugify($this->title));
        }
        if (!$this->sortorder) {
            $this->sortorder = $this->getUpperSortorder();
        }

    }

    public function afterValidationOnUpdate()
    {
        if ($this->hasChanged('parent_id')) {
            $this->sortorder = $this->getUpperSortorder() + 1;
        }

    }

    public function afterValidationOnCreate()
    {
        $this->sortorder = $this->getUpperSortorder() + 1;

    }

    public function getUpperSortorder()
    {
        $conditions = null;
        if ($this->parent_id) {
            $conditions = array("type = '{$this->type}' AND parent_id = {$this->parent_id}");
        } else {
            $conditions = array("type = '{$this->type}' AND parent_id IS NULL");
        }
        $count = self::count($conditions);
        return $count;

    }

    public function getId()
    {
        return $this->id;

    }

    public function getType()
    {
        return $this->type;

    }

    public function getTypes()
    {
        return self::$types;

    }

    public function getParent_id()
    {
        return $this->parent_id;

    }

    public function getCachedParentCategory()
    {
        if ($this->parent_id) {
            return self::findCachedById($this->parent_id);
        }

    }

    public function getCategoryPath()
    {
        $path = array();
        $path[] = $this;
        if ($this->getCachedParentCategory()) {
            foreach (array_reverse($this->getCachedParentCategory()->getCategoryPath()) as $parent) {
                $path[] = $parent;
            }
        }
        return array_reverse($path);

    }

    public function getCategoryPathTitles($glue = ' \ ', $reverse = false, $metaTitle = false, $firstSuffix = null)
    {
        $result = array();
        $path = $this->getCategoryPath();
        if (!empty($path)) {
            foreach ($path as $el) {
                if ($metaTitle) {
                    $result[] = $el->getMeta_title();
                } else {
                    $result[] = $el->getTitle();
                }
            }
        }
        if ($reverse) {
            $result = array_reverse($result);
        }
        if ($firstSuffix) {
            $result[0] .= $firstSuffix;
        }
        return implode($glue, $result);

    }

    public function getUpperParent()
    {
        $path = $this->getCategoryPath();
        return $path[0];

    }

    public function getChildren($cached = true)
    {
        $parameters = array(
            "conditions" => "parent_id = $this->id",
            "order" => "sortorder ASC",
        );
        if ($cached) {
            $parameters['cache'] = array(
                'lifetime' => 60,
                'key' => CACHE_PREFIX . md5('Category(' . $this->id . ')->getChildren()'),
            );
        }

        $children = $this->find($parameters);
        return $children;

    }

    public function getChildrenIds()
    {
        $children = $this->getChildren();
        $result = array();
        if ($children) {
            foreach ($children as $child) {
                $result[] = $child->getId();
            }
        }
        return $result;

    }

    public static function findCachedById($id)
    {
        return self::findFirst(array("id = $id", 'cache' =>
            array('lifetime' => 60, 'key' => CACHE_PREFIX . md5('Category(' . $id . ')'))
        ));

    }

    public static function findCachedBySlug($slug)
    {
        return self::findFirst(array("slug = '$slug'", 'cache' =>
            array('lifetime' => 60, 'key' => CACHE_PREFIX . md5('Category(' . $slug . ')'))
        ));

    }

    public static function getCategoriesTreeByType($type = 'publication', $parent_id = null, $cache = true, $self_id = null)
    {
        if ($parent_id) {
            $parentPartSQL = 'parent_id = ' . $parent_id;
        } else {
            $parentPartSQL = 'parent_id IS NULL';
        }
        $parameters = array(
            $parentPartSQL . ' AND type = "' . $type . '"',
        );

        if ($self_id) {
            $parameters[0] .= " AND id <> $self_id";
        }
        if ($cache) {
            $parameters['cache'] = array(
                'lifetime' => 60,
                'key' => CACHE_PREFIX . md5('CategoriesTreeByType(' . $type . '_' . $parent_id . ')'),
            );
        }
        $parameters['order'] = 'sortorder ASC';
        $tops = self::find($parameters);
        foreach ($tops as $category) {
            $children = $category->getChildren($cache);
            if ($children) {
                $category->children = $category->getChildren($cache);
            }
        }
        return $tops;

    }

    public function getTitle()
    {
        return $this->getMLVariable('title');

    }

    public function getTitle_uk()
    {
        return $this->title_uk;

    }

    public function getSlug()
    {
        return $this->slug;

    }

    public function getMeta_title()
    {
        return $this->getMLVariable('meta_title');

    }

    public function getMeta_title_uk()
    {
        return $this->meta_title_uk;

    }

    public function getMeta_description()
    {
        return $this->getMLVariable('meta_description');

    }

    public function getMeta_description_uk()
    {
        return $this->meta_description_uk;

    }

    public function getMeta_keywords()
    {
        return $this->getMLVariable('meta_keywords');

    }

    public function getMeta_keywords_uk()
    {
        return $this->meta_keywords_uk;

    }

    public function getText()
    {
        return $this->getMLVariable('text');

    }

    public function getText_uk()
    {
        return $this->text_uk;

    }

    public function setId($id)
    {
        $this->id = $id;

    }

    public function setType($type)
    {
        $this->type = $type;

    }

    public function setParent_id($parent_id = null)
    {
        if (!$parent_id) {
            return $this->parent_id = null;
        }
        $this->parent_id = $parent_id;

    }

    public function setTitle($title)
    {
        $this->title = $title;

    }

    public function setTitle_uk($title_uk)
    {
        $this->title_uk = $title_uk;

    }

    public function setSlug($slug)
    {
        $this->slug = $slug;

    }

    public function setMeta_title($meta_title)
    {
        $this->meta_title = $meta_title;

    }

    public function setMeta_title_uk($meta_title_uk)
    {
        $this->meta_title_uk = $meta_title_uk;

    }

    public function setMeta_description($meta_description)
    {
        $this->meta_description = $meta_description;

    }

    public function setMeta_description_uk($meta_description_uk)
    {
        $this->meta_description_uk = $meta_description_uk;

    }

    public function setMeta_keywords($meta_keywords)
    {
        $this->meta_keywords = $meta_keywords;

    }

    public function setMeta_keywords_uk($meta_keywords_uk)
    {
        $this->meta_keywords_uk = $meta_keywords_uk;

    }

    public function setText($text)
    {
        $this->text = $text;

    }

    public function setText_uk($text_uk)
    {
        $this->text_uk = $text_uk;

    }

    public function getSortorder()
    {
        return $this->sortorder;

    }

    public function setSortorder($sortorder)
    {
        $this->sortorder = $sortorder;

    }

    /**
     * @param mixed $visible
     */
    /*public function setVisible($visible)
    {
        $this->visible = $visible;
    }*/

    /**
     * @return mixed
     */
    public function getVisible()
    {
        return $this->visible;
    }


}
