<?php

/**
 * Helper
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Application\Mvc;

use Application\Mvc\Router\DefaultRouter;
use Cms\Model\Language;

class Helper extends \Phalcon\Mvc\User\Component
{
    const StaticWidgetDefaultOptions = [
        'lifetime' => 120
    ];

    private $translate = null;
    private $admin_translate = null;

    public $menu;

    public function __construct()
    {
        $this->menu = \Menu\Helper\Menu::getInstance();
    }

    /**
     * Мультиязычный перевод строки по сайту/пользовательской_части
     */
    public function translate($string, $placeholders = null)
    {
        if (!$this->translate) {
            $this->translate = $this->getDi()->get('translate');
        }
        return $this->translate->query($string, $placeholders);

    }

    /**
     * Мультиязычный перевод строки по админке
     */
    public function at($string, $placeholders = null)
    {
        if (!$this->admin_translate) {
            $this->admin_translate = $this->getDi()->get('admin_translate');
        }
        return $this->admin_translate->query($string, $placeholders);

    }

    public function widget($namespace = 'Index', array $params = [])
    {
        return new \Application\Widget\Proxy($namespace, $params);
    }

    /**
     * Вызов выджета из модуля StaticWidget
     * @param $id - идентификатор виджета, например "phone"
     */
    public function staticWidget($id, $params = [])
    {
        $mergeConfig = array_merge(self::StaticWidgetDefaultOptions, $params);
        $widget = \Widget\Model\Widget::findFirst(["id='{$id}'", "cache" => ["lifetime" => $mergeConfig["lifetime"], "key" => HOST_HASH . md5("Widget::findFirst({$id})")]]);
        if ($widget) {
            return $widget->getHtml();
        }
    }

    public function langUrl($params)
    {
        $routeName = $params['for'];
        $routeName = DefaultRouter::ML_PREFIX . $routeName . '_' . LANG;
        $params['for'] = $routeName;
        return $this->url->get($params);
    }

    public function languages()
    {
        return Language::findCachedLanguages();

    }

    public function langSwitcher($lang, $string)
    {
        $helper = new \Application\Mvc\Helper\LangSwitcher();
        return $helper->render($lang, $string);
    }

    public function cacheExpire($seconds)
    {
        $response = $this->getDi()->get('response');
        $expireDate = new \DateTime();
        $expireDate->modify("+$seconds seconds");
        $response->setExpires($expireDate);
        $response->setHeader('Cache-Control', "max-age=$seconds");
    }

    public function isAdminSession()
    {
        $session = $this->getDi()->get('session');
        $auth = $session->get('auth');
        if ($auth) {
            if ($auth->admin_session == true) {
                return true;
            }
        }
    }

    public function error($code = 404)
    {
        $helper = new \Application\Mvc\Helper\ErrorReporting();
        return $helper->{'error' . $code}();

    }

    public function title($title = null, $h1 = false)
    {
        return \Application\Mvc\Helper\Title::getInstance($title, $h1);
    }

    public function meta()
    {
        return \Application\Mvc\Helper\Meta::getInstance();
    }

    public function activeMenu()
    {
        return \Application\Mvc\Helper\ActiveMenu::getInstance();
    }

    public function announce($incomeString, $num)
    {
        $object = new \Application\Mvc\Helper\Announce();
        return $object->getString($incomeString, $num);
    }

    public function dbProfiler()
    {
        $object = new \Application\Mvc\Helper\DbProfiler();
        return $object->DbOutput();
    }

    public function constant($name)
    {
        return get_defined_constants()[$name];
    }

    public function image($args, $attributes = [])
    {
        $imageFilter = new \Image\Storage($args, $attributes);
        return $imageFilter;
    }

    public function querySymbol()
    {
        $object = new \Application\Mvc\Helper\RequestQuery();
        return $object->getSymbol();
    }

    public function javascript($id)
    {
        $javascript = \Cms\Model\Javascript::findCachedById($id);
        if ($javascript) {
            return $javascript->getText();
        }
    }

    public function modulePartial($template, $data, $module = null)
    {
        $view = clone $this->getDi()->get('view');
        $partialsDir = '';
        if ($module) {
            $moduleName = \Application\Utils\ModuleName::camelize($module);
            $partialsDir = '../../../modules/' . $moduleName . '/views/';
        }
        $view->setPartialsDir($partialsDir);

        return $view->partial($template, $data);
    }

}
