<?php
/**
 * @copyright Copyright (c) 2011 - 2015 Oleksandr Torosh (http://yonastudio.com)
 * @author Oleksandr Torosh <webtorua@gmail.com>
 */

namespace Cms;

class Config
{

    public static function get()
    {
        $application = include_once APPLICATION_PATH . '/config/environment/' . APPLICATION_ENV . '.php';

        $config_default = [
            'loader'    => [
                'namespaces' => [
                    'YonaCMS\Plugin' => APPLICATION_PATH . '/plugins/',
                    'Application'    => APPLICATION_PATH . '/modules/Application',
                    'Cms'            => APPLICATION_PATH . '/modules/Cms',
                ],
            ],
            'modules'   => [
                'cms' => [
                    'className' => 'Cms\Module',
                    'path'      => APPLICATION_PATH . '/modules/Cms/Module.php'
                ],
            ],
            'base_path' => (isset($application['base_path'])) ? $application['base_path'] : null,
            'database'  => (isset($application['database'])) ? $application['database'] : null,
            'cache'     => (isset($application['cache'])) ? $application['cache'] : null,
            'memcache'  => (isset($application['memcache'])) ? $application['memcache'] : null,
            'memcached'  => (isset($application['memcached'])) ? $application['memcached'] : null,
            'assets'    => (isset($application['assets'])) ? $application['assets'] : null,
        ];

        $global = include_once APPLICATION_PATH . '/config/global.php';

        // Modules configuration list
        $modules_list = include_once APPLICATION_PATH . '/config/modules.php';
        require_once APPLICATION_PATH . '/modules/Application/Loader/Modules.php';
        $modules = new \Application\Loader\Modules();
        $modules_config = $modules->modulesConfig($modules_list);

        $config = array_merge_recursive($config_default, $global, $modules_config);

        return new \Phalcon\Config($config);
    }

}
