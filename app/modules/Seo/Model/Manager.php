<?php
/**
 * @copyright Copyright (c) 2011 - 2014 Oleksandr Torosh (http://wezoom.net)
 * @author Oleksandr Torosh <web@wezoom.net>
 */

namespace Seo\Model;


use Application\Mvc\Helper\CmsCache;
use Application\Mvc\Model\Model;
use Application\Mvc\Router\DefaultRouter;
use Phalcon\Mvc\Model\Message;

class Manager extends Model
{

    public function getSource()
    {
        return "seo_manager";
    }

    public $id;
    public $url;
    public $head_title;
    public $meta_description;
    public $meta_keywords;
    public $seo_text;
    public $created_at;
    public $updated_at;

    public function validation()
    {
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

    public function afterSave()
    {
        CmsCache::getInstance()->save('seo_manager', $this->buildCmsSeoManagerCache());
    }

    public function afterDelete()
    {
        CmsCache::getInstance()->save('seo_manager', $this->buildCmsSeoManagerCache());
    }

    private function buildCmsSeoManagerCache()
    {
        $entries = self::find();
        $save = [];
        if (!empty($entries)) {
            foreach ($entries as $el) {
                $save[$el->getUrl()] = [
                    'id'               => $el->getId(),
                    'url'              => $el->getUrl(),
                    'head_title'       => $el->getHead_title(),
                    'meta_description' => $el->getMeta_description(),
                    'meta_keywords'    => $el->getMeta_keywords(),
                    'seo_text'         => $el->getSeo_text(),
                ];
            }
        }
        return $save;
    }

    public static function urls()
    {
        return CmsCache::getInstance()->get('seo_manager');
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $request = $this->getDI()->get('request');
        $host = $request->getHttpHost();
        $url = str_replace(['http://' . $host, 'https://' . $host], ['', ''], $url);
        $this->url = $url;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function setHead_title($head_title)
    {
        $this->head_title = $head_title;
    }

    public function getHead_title()
    {
        return $this->head_title;
    }

    public function setMeta_description($meta_description)
    {
        $this->meta_description = $meta_description;
    }

    public function getMeta_description()
    {
        return $this->meta_description;
    }

    public function setMeta_keywords($meta_keywords)
    {
        $this->meta_keywords = $meta_keywords;
    }

    public function getMeta_keywords()
    {
        return $this->meta_keywords;
    }

    public function setSeo_text($seo_text)
    {
        $this->seo_text = $seo_text;
    }

    public function getSeo_text()
    {
        return $this->seo_text;
    }


}