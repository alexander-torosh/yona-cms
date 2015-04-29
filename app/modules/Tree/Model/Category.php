<?php
/**
 * @copyright Copyright (c) 2011 - 2015 Oleksandr Torosh (http://yonastudio.com)
 * @author Oleksandr Torosh <webtorua@gmail.com>
 */

namespace Tree\Model;

use Application\Mvc\Model;
use Phalcon\Mvc\Model\Validator\Uniqueness;

class Category extends Model
{

    public function getSource()
    {
        return "tree_category";
    }

    protected $translateModel = 'Tree\Model\Translate\CategoryTranslate'; // translate

    private $id;
    private $root = 'articles';
    private $parent_id;
    private $slug;
    private $title; // translate
    private $depth = 0;
    private $left;
    private $right;
    private $created_at;
    private $updated_at;

    public static $roots = [
        'articles' => 'Articles',
        'news'     => 'News'
    ];

    public function initialize()
    {
        $this->belongsTo('parent_id', 'Category\Model\Category', 'id', ['alias' => 'Parent']);
    }

    public function validation()
    {
        $this->validate(new Uniqueness(
            [
                "field"   => "slug",
                "message" => "Category with slug '" . $this->slug . "' is already exists. Take another title"
            ]
        ));

        return $this->validationHasFailed() != true;
    }

    public function beforeCreate()
    {
        $this->created_at = date("Y-m-d H:i:s");
    }

    public function beforeUpdate()
    {
        $this->updated_at = date("Y-m-d H:i:s");
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @param string $root
     */
    public function setRoot($root)
    {
        $this->root = $root;
    }

    /**
     * @return mixed
     */
    public function getParentId()
    {
        return $this->parent_id;
    }

    /**
     * @param mixed $parent_id
     */
    public function setParentId($parent_id)
    {
        $this->parent_id = $parent_id;
    }

    /**
     * @return mixed
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * @param mixed $depth
     */
    public function setDepth($depth)
    {
        $this->depth = $depth;
    }

    /**
     * @return mixed
     */
    public function getLeft()
    {
        return $this->left;
    }

    /**
     * @param mixed $left
     */
    public function setLeft($left)
    {
        $this->left = $left;
    }

    /**
     * @return mixed
     */
    public function getRight()
    {
        return $this->right;
    }

    /**
     * @param mixed $right
     */
    public function setRight($right)
    {
        $this->right = $right;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
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
    public function getTitle()
    {
        return $this->getMLVariable('title');
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
    public function getCreatedAt()
    {
        return $this->created_at;
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
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param mixed $updated_at
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }


}