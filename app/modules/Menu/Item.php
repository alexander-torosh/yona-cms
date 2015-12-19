<?php
/**
 * @copyright Copyright (c) 2011 - 2015 Oleksandr Torosh (http://wezoom.net)
 * @author Oleksandr Torosh <web@wezoom.net>
 */

namespace Menu;

class Item
{

    public $title;
    public $id;
    public $url;
    public $params;
    public $children = [];

    private $href;
    private $a_attributes = [];
    private $a_attributes_str;
    private $active_items = [];

    /**
     * @param $title
     * @param integer $id
     * @param string $url
     * @param array $params
     */
    public function __construct($title, $id = null, $url = null, array $params = [])
    {
        $this->title = $title;
        $this->id = $id;
        $this->url = $url;
        $this->params = $params;

        if (isset($this->params['a'])) {
            $this->a_attributes = $this->params['a'];
        }
    }

    public function make()
    {
        $this->makeAClass();
        //$this->makeLiAttributesStr();
        $this->makeAAttributesStr();

        $this->href = ($this->url) ? $this->url : "javascript:void(0);";
    }

    private function makeAClass()
    {
        $class = ['item'];
        if (!empty($this->children)) {
            $class[] = 'parent';
        }
        if (in_array($this->id, $this->active_items)) {
            $class[] = 'active';
        } elseif (!empty($this->children)) {
            foreach ($this->children as $child) {
                if (in_array($child->id, $this->active_items)) {
                    $class[] = 'active';
                }
            }
        }

        $this->a_attributes['class'] = implode(' ', $class);
    }

    private function makeAAttributesStr()
    {
        if (!empty($this->a_attributes)) {
            foreach ($this->a_attributes as $key => $value) {
                $this->a_attributes_str .= ' ' . $key . '="' . $value . '"';
            }
        }
    }

    public function setActiveItems($active_items)
    {
        $this->active_items = $active_items;
    }

    public function setChildren(array $children = [])
    {
        $this->children = $children;
    }

    public function render()
    {
        $html = "<a href=\"{$this->href}\"{$this->a_attributes_str}>{$this->title}</a>\n";

        if (!empty($this->children)) {
            $html .= "<div>";
            foreach ($this->children as $child) {
                $childItem = new Item($child->title, $child->id, $child->url, $child->params);
                $childItem->setActiveItems($this->active_items);
                $childItem->make();
                $html .= $childItem->render();
            }
            $html .= "</div>";
        }

        return $html;
    }

    public function __toString()
    {
        return $this->render();
    }

} 