<?php
 /**
 * @copyright Copyright (c) 2011 - 2014 Oleksandr Torosh (http://wezoom.net)
 * @author Oleksandr Torosh <web@wezoom.net>
 */

namespace Seo\Form;


use Application\Form\Form;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;

class ManagerForm extends Form
{

    public function initialize()
    {
        $config = $this->getDi()->get('config');
        $modules = $config->modules->toArray();
        $modulesArray = array('' => ' - ');
        foreach($modules as $module => $val) {
            $modulesArray[$module] = $module;
        }

        $registry = $this->getDi()->get('registry');
        $languages = $registry->cms['languages'];
        $languagesArray = array('' => ' - ');
        foreach($languages as $lang) {
            $languagesArray[$lang['iso']] = $lang['name'];
        }

        $this->add((new Text('custom_name'))->setLabel('Рабочее имя, для удобства'));
        $this->add((new Text('route'))->setLabel('Route'));
        $this->add((new Select('module', $modulesArray))->setLabel('Module'));
        $this->add((new Text('controller'))->setLabel('Controller'));
        $this->add((new Text('action'))->setLabel('Action'));
        $this->add((new Select('language', $languagesArray))->setLabel('Язык'));
        $this->add((new TextArea('route_params_json', array('data-description' => 'Пример: {"type" : "news", "page" : 1}')))->setLabel('Параметры Route. JSON'));
        $this->add((new TextArea('query_params_json', array('data-description' => 'Пример: {"limit" : 10, "display" : "table"}')))->setLabel('Параметры GET. JSON'));
        $this->add((new Text('head_title'))->setLabel('title'));
        $this->add((new TextArea('meta_description'))->setLabel('meta-description'));
        $this->add((new TextArea('meta_keywords'))->setLabel('meta-keywords'));
        $this->add((new TextArea('seo_text'))->setLabel('SEO текст'));

    }

} 