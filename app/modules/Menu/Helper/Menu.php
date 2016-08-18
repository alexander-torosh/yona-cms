<?php
/**
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.net)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Menu\Helper;

use Menu\Item;

class Menu
{

    private static $instance;
    private $active_items = [];

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Menu();
        }
        return self::$instance;
    }

    public function __construct()
    {

    }

    public function item($title, $id = null, $url = null, array $params = [], array $children = [])
    {
        $item = new Item($title, $id, $url, $params);
        if (!empty($children)) {
            $item->setChildren($children);
        }
        $item->setActiveItems($this->active_items);
        $item->make();
        return $item;
    }

    public function setActive($id)
    {
        if (!in_array($id, $this->active_items)) {
            $this->active_items[] = $id;
        }
    }

}