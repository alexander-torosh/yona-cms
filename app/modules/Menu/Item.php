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

    private $li_attributes_str;
    private $href;
    private $a_attributes_str;
    private $active_items = [];

    public function __construct($title, $id = null, $url = null, array $params = [])
    {
        $this->title = $title;
        $this->id = $id;
        $this->url = $url;
        $this->params = $params;
    }

    public function make()
    {
        $li_attributes = [];
        $a_attributes = [];

        $li_class = '';
        $li_class_active = false;
        if (!empty($this->children)) {
            $li_class = 'parent';
        }
        if (isset($this->params['li_class'])) {
            $li_class .= $this->params['li_class'];
        }
        if (isset($this->params['class'])) {
            $a_attributes['class'] = $this->params['class'];
        }
        if (in_array($this->id, $this->active_items)) {
            $li_class_active = true;
        } else {
            if (!empty($this->children)) {
                foreach ($this->children as $child) {
                    if (in_array($child->id, $this->active_items)) {
                        $li_class_active = true;
                    }
                }
            }
        }
        if ($li_class_active) {
            if ($li_class) {
                $li_class .= ' ';
            }
            $li_class .= 'active';
        }
        if ($li_class) {
            $li_attributes['class'] = $li_class;
        }

        if (!empty($li_attributes)) {
            foreach ($li_attributes as $key => $value) {
                $this->li_attributes_str .= ' '.$key.'="'.$value.'"';
            }
        }
        if (!empty($a_attributes)) {
            foreach ($a_attributes as $key => $value) {
                $this->a_attributes_str .= ' '.$key.'="'.$value.'"';
            }
        }

        $this->href = ($this->url) ? $this->url : "javascript:void(0);";
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
        $html = "<li{$this->li_attributes_str}>
            <a href=\"{$this->href}\"{$this->a_attributes_str}>{$this->title}</a>\n";

        if (!empty($this->children)) {
            $html .= "<ul>";
            foreach ($this->children as $child) {
                $childItem = new MenuItem($child->title, $child->id, $child->url, $child->params);
                $childItem->setActiveItems($this->active_items);
                $childItem->make();
                $html .= $childItem->render();
            }
            $html .= "</ul>";
        }

        $html .= "</li>\n";

        return $html;
    }

    public function __toString()
    {
        return $this->render();
    }

} 