<?php

/**
 * @author Oleksandr Torosh <webtorua@gmail.com>
 */
namespace Yona;

use Phalcon\Mvc\User\Component;
use Phalcon\Text;

class ModulesLoader extends Component
{
    private $namespaces = [];
    private $modules = [];

    public function __construct()
    {
        $modules_list = include_once BASE_PATH . '/app/config/modules.php';

        $namespaces = [];
        $modules = [];
        if (!empty($modules_list)) {
            foreach ($modules_list as $module) {
                $namespaces[$module] = BASE_PATH . '/app/modules/' . $module;
                $simple = Text::uncamelize($module);
                $simple = str_replace('_', '-', $simple);
                $modules[$simple] = [
                    'className' => $module . '\Module',
                    'path'      => BASE_PATH . '/app/modules/' . $module . '/Module.php'
                ];
            }
            $this->setNamespaces($namespaces);
            $this->setModules($modules);
        }
    }

    /**
     * @return array
     */
    public function getNamespaces()
    {
        return $this->namespaces;
    }

    /**
     * @param array $namespaces
     */
    public function setNamespaces($namespaces)
    {
        $this->namespaces = $namespaces;
    }

    /**
     * @return array
     */
    public function getModules()
    {
        return $this->modules;
    }

    /**
     * @param array $modules
     */
    public function setModules($modules)
    {
        $this->modules = $modules;
    }

}