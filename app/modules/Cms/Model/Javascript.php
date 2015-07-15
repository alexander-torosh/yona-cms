<?php
/**
     * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.net)
     * @author Aleksandr Torosh <webtorua@gmail.com>
     */

namespace Cms\Model;

use Phalcon\Mvc\Model;

class Javascript extends Model
{

    public function getSource()
    {
        return "cms_javascript";
    }

    public $id;
    public $text;

    public function afterUpdate()
    {
        $cache = $this->getDi()->get('cache');
        $key = HOST_HASH . md5("Javascript::getCachedScript({$this->id})");
        $cache->delete($key);
    }

    public static function findCachedById($id)
    {
        $key = HOST_HASH . md5("Javascript::getCachedScript($id)");
        $result = self::findFirst(array("id ='{$id}'",
            'cache' => array(
                'key' => $key,
                'lifetime' => 1200, //20 min
            )
        ));
        return $result;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @param mixed
     */
    public function getText()
    {
        return $this->text;
    }

}