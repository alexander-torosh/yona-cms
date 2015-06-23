<?php
/**
 * @copyright Copyright (c) 2011 - 2014 Oleksandr Torosh (http://wezoom.net)
 * @author Oleksandr Torosh <web@wezoom.net>
 */

namespace Seo\Model;


use Application\Mvc\Model;
use Application\Mvc\Router\DefaultRouter;
use Phalcon\Mvc\Model\Message;

class Manager extends Model
{

    public function getSource()
    {
        return "seo_manager";
    }

    public $id;
    public $custom_name;
    public $type;
    public $url;
    public $route;
    public $route_ml;
    public $module;
    public $controller;
    public $action;
    public $language;
    public $route_params_json;
    public $query_params_json;
    public $head_title;
    public $meta_description;
    public $meta_keywords;
    public $seo_text;
    public $created_at;
    public $updated_at;

    public static $types = [
        'url'   => 'URL',
        'route' => 'Route',
        'mca'   => 'Model Controller Action',
    ];

    public function validation()
    {
        $helper = $this->getDI()->get('helper');

        if ($this->type == 'url') {
            if (!$this->getUrl()) {
                $message = new Message($helper->at('Укажите URL'));
                $this->appendMessage($message);
                return false;
            }
        }

        if ($this->type == 'route') {
            if ($this->route || $this->route_params_json) {
                if ($this->module || $this->controller || $this->action) {
                    $message = new Message($helper->at('Необходимо использовать Route или Module-Controller-Action. Одновременное указание параметров невозможно'));
                    $this->appendMessage($message);
                    return false;
                }
            }
            if ($this->route_params_json) {
                $valid_json = json_decode($this->route_params_json);
                if (!$valid_json) {
                    $message = new Message($helper->at('Параметры Route должны быть в формате JSON'));
                    $this->appendMessage($message);
                    return false;
                }
            }
        }

        if ($this->query_params_json) {
            $valid_json = json_decode($this->query_params_json);
            if (!$valid_json) {
                $message = new Message($helper->at('Параметры GET должны быть в формате JSON'));
                $this->appendMessage($message);
                return false;
            }
        }

        return $this->validationHasFailed() != true;
    }

    public function afterValidation()
    {
        if ($this->language) {
            $this->route_ml = DefaultRouter::ML_PREFIX.$this->route.'_'.$this->language;
        } else {
            $this->route_ml = $this->route;
        }
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
        $cache = $this->getDI()->get('cache');
        $cache->delete(self::routeCacheKey($this->route_ml, $this->language));
        $cache->delete(self::mcaCacheKey($this->module, $this->controller, $this->action, $this->language));
    }

    public function getType()
    {
        return $this->type;
    }

    public function getTypeTitle()
    {
        return self::$types[$this->type];
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function setAction($action)
    {
        $this->action = $action ? $action : null;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setController($controller)
    {
        $this->controller = $controller ? $controller : null;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCustomName($custom_name)
    {
        $this->custom_name = $custom_name;
    }

    public function getCustomName()
    {
        return $this->custom_name;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setLanguage($language)
    {
        $this->language = ($language) ? $language : null;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function setModule($module)
    {
        $this->module = ($module) ? $module : null;
    }

    public function getModule()
    {
        return $this->module;
    }

    public function setQueryParamsJson($query_params_json)
    {
        $this->query_params_json = ($query_params_json) ? $query_params_json : null;
    }

    public function getQueryParamsJson()
    {
        return $this->query_params_json;
    }

    public function setRouteParamsJson($route_params_json)
    {
        $this->route_params_json = ($route_params_json) ? $route_params_json : null;
    }

    public function getRouteParamsJson()
    {
        return $this->route_params_json;
    }

    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function setRoute($route)
    {
        $this->route = $route;
    }

    public static function urlCacheKey($url)
    {
        $key = HOST_HASH.md5('Seo\Model\Manager::url::'.$url);
        return $key;
    }

    public static function routeCacheKey($route_name, $lang)
    {
        $key = HOST_HASH.md5('Seo\Model\Manager::'.DefaultRouter::ML_PREFIX.$route_name.'_'.$lang);
        return $key;
    }

    public static function mcaCacheKey($module, $controller, $action, $lang)
    {
        $key = HOST_HASH.md5('Seo\Model\Manager::'.$module.'::'.$controller.'::'.$action.'_'.$lang);
        return $key;
    }

    /**
     * @param mixed $route_ml
     */
    public function setRouteMl($route_ml)
    {
        $this->route_ml = $route_ml;
    }

    /**
     * @return mixed
     */
    public function getRouteMl()
    {
        return $this->route_ml;
    }

    /**
     * @param mixed $head_title
     */
    public function setHead_title($head_title)
    {
        $this->head_title = $head_title;
    }

    /**
     * @return mixed
     */
    public function getHead_title()
    {
        return $this->head_title;
    }

    /**
     * @param mixed $meta_description
     */
    public function setMeta_description($meta_description)
    {
        $this->meta_description = $meta_description;
    }

    /**
     * @return mixed
     */
    public function getMeta_description()
    {
        return $this->meta_description;
    }

    /**
     * @param mixed $meta_keywords
     */
    public function setMeta_keywords($meta_keywords)
    {
        $this->meta_keywords = $meta_keywords;
    }

    /**
     * @return mixed
     */
    public function getMeta_keywords()
    {
        return $this->meta_keywords;
    }

    /**
     * @param mixed $seo_text
     */
    public function setSeo_text($seo_text)
    {
        $this->seo_text = $seo_text;
    }

    /**
     * @return mixed
     */
    public function getSeo_text()
    {
        return $this->seo_text;
    }


}