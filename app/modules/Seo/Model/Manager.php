<?php
/**
 * @copyright Copyright (c) 2011 - 2014 Oleksandr Torosh (http://wezoom.net)
 * @author Oleksandr Torosh <web@wezoom.net>
 */

namespace Seo\Model;


use Application\Mvc\Model;
use Phalcon\Mvc\Model\Message;

class Manager extends Model
{

    public function getSource()
    {
        return "seo_manager";
    }

    protected $translateModel = 'Seo\Model\Translate\ManagerTranslate';  // translate

    public $id;
    public $custom_name;
    public $route;
    public $module;
    public $controller;
    public $action;
    public $language;
    public $route_params_json;
    public $query_params_json;
    public $head_title; // translate
    public $meta_description; // translate
    public $meta_keywords; // translate
    public $seo_text; // translate
    public $created_at;
    public $updated_at;

    public function initialize()
    {
        $this->hasMany("id", $this->translateModel, "foreign_id"); // translate
    }

    public function validation()
    {
        if ($this->route || $this->route_params_json) {
            if ($this->module || $this->controller || $this->action) {
                $message = new Message('Необходимо использовать Route или Module-Controller-Action. Одновременное указание параметров невозможно');
                $this->appendMessage($message);
                return false;
            }
        }
        if ($this->route_params_json) {
            $valid_json = json_decode($this->route_params_json);
            if (!$valid_json) {
                $message = new Message('Параметры Route должны быть в формате JSON');
                $this->appendMessage($message);
                return false;
            }
        }
        if ($this->query_params_json) {
            $valid_json = json_decode($this->query_params_json);
            if (!$valid_json) {
                $message = new Message('Параметры GET должны быть в формате JSON');
                $this->appendMessage($message);
                return false;
            }
        }

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

    public function setAction($action)
    {
        $this->action = $action;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setController($controller)
    {
        $this->controller = $controller;
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

    public function setHead_title($head_title)
    {
        $this->setMLVariable('head_title', $head_title);
    }

    public function getHead_title()
    {
        return $this->getMLVariable('head_title');
    }

    public function getId()
    {
        return $this->id;
    }

    public function setLanguage($language)
    {
        $this->language = ($language) ? $language : null ;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function setMeta_description($meta_description)
    {
        $this->setMLVariable('meta_description', $meta_description);
    }

    public function getMeta_description()
    {
        return $this->getMLVariable('meta_description');
    }

    public function setMeta_keywords($meta_keywords)
    {
        $this->setMLVariable('meta_keywords', $meta_keywords);
    }

    public function getMeta_keywords()
    {
        return $this->getMLVariable('meta_keywords');
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
        $this->query_params_json = $query_params_json;
    }

    public function getQueryParamsJson()
    {
        return $this->query_params_json;
    }

    public function setRouteParamsJson($route_params_json)
    {
        $this->route_params_json = $route_params_json;
    }

    public function getRouteParamsJson()
    {
        return $this->route_params_json;
    }

    public function setSeo_text($seo_text)
    {
        $this->setMLVariable('seo_text', $seo_text);
    }

    public function getSeo_text()
    {
        return $this->getMLVariable('seo_text');
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

    public static function routeCacheKey($route_name, $lang)
    {
        $key = HOST_HASH . md5($route_name . $lang);
        return $key;
    }

}