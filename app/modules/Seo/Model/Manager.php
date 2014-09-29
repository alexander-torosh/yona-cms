<?php
/**
 * @copyright Copyright (c) 2011 - 2014 Oleksandr Torosh (http://wezoom.net)
 * @author Oleksandr Torosh <web@wezoom.net>
 */

namespace Seo\Model;


use Application\Mvc\Model;

class Manager extends Model
{

    public function getSource()
    {
        return "seo_manager";
    }

    public $id;
    public $custom_name;
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

    public function setHeadTitle($head_title)
    {
        $this->head_title = $head_title;
    }

    public function getHeadTitle()
    {
        return $this->head_title;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setLanguage($language)
    {
        $this->language = $language;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function setMetaDescription($meta_description)
    {
        $this->meta_description = $meta_description;
    }

    public function getMetaDescription()
    {
        return $this->meta_description;
    }

    public function setMetaKeywords($meta_keywords)
    {
        $this->meta_keywords = $meta_keywords;
    }

    public function getMetaKeywords()
    {
        return $this->meta_keywords;
    }

    public function setModule($module)
    {
        $this->module = $module;
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

    public function setSeoText($seo_text)
    {
        $this->seo_text = $seo_text;
    }

    public function getSeoText()
    {
        return $this->seo_text;
    }

    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }


}