<?php
 /**
 * @copyright Copyright (c) 2011 - 2014 Oleksandr Torosh (http://wezoom.net)
 * @author Oleksandr Torosh <web@wezoom.net>
 */

namespace Seo\Form;


use Application\Form\Form;
use Cms\Model\Language;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Mvc\Model\Query\Lang;

class ManagerForm extends Form
{

    public function initialize()
    {
        $config = $this->getDi()->get('config');
        $modules = $config->modules->toArray();
        $modulesArray = array('' => ' - ');
        foreach($modules as $module => $val) {
            if (!in_array($module, array('cms','image','admin','widget','file-manager','seo','slider'))) {
                $modulesArray[$module] = $module;
            }
        }

        $languages = Language::findCachedLanguages();
        $languagesArray = array('' => ' - ');
        foreach($languages as $lang) {
            $languagesArray[$lang->getIso()] = $lang->getName();
        }

        $this->add((new Text('custom_name'))->setLabel('Business name, for convenience'));
        $this->add((new Text('route'))->setLabel('Route'));
        $this->add((new Select('module', $modulesArray))->setLabel('Module'));
        $this->add((new Text('controller'))->setLabel('Controller'));
        $this->add((new Text('action'))->setLabel('Action'));
        $this->add((new Select('language', $languagesArray,array('data-description' => 'If the router is multilingual - Indicates the language')))->setLabel('Language'));
        $this->add((new TextArea('route_params_json', array('data-description' => 'Example: {"type" : "news", "page" : 1}')))->setLabel('Route Parameters. JSON'));
        $this->add((new TextArea('query_params_json', array('data-description' => 'Example: {"limit" : 10, "display" : "table"}')))->setLabel('Parameters GET. JSON'));
        $this->add((new Text('head_title'))->setLabel('title'));
        $this->add((new TextArea('meta_description'))->setLabel('meta-description'));
        $this->add((new TextArea('meta_keywords'))->setLabel('meta-keywords'));
        $this->add((new TextArea('seo_text'))->setLabel('SEO Text'));

    }

} 