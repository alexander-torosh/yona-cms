<?php

$application = include_once APPLICATION_ENV . '/application.php';

/**
 * ============================================================================
 * Configuration
 */
$config = [
    'loader'         => [
        'namespaces' => [
            'Application' => APPLICATION_PATH . '/modules/Application',
            'Cms'         => APPLICATION_PATH . '/modules/Cms',

            // Setup yours vendors namespaces, example:
            // 'Zend' => APPLICATION_PATH . '/../vendor/zendframework/zendframework/library/Zend',

        ],
    ],
    'modules'        => [
        'cms' => [
            'className' => 'Cms\Module',
            'path'      => APPLICATION_PATH . '/modules/Cms/Module.php'
        ],
    ],
    'database'       => (isset($application['database'])) ? $application['database'] : null,
    'cache'          => (isset($application['cache'])) ? $application['cache'] : null,
    'memcahce'       => (isset($application['memcahce'])) ? $application['memcahce'] : null,
    'admin_language' => 'en', // ru, en. All translations contains in /app/modules/Cms/admin_translations
];
/**
 * ============================================================================
 */


// Формирование конфигурационного списка модулей
$modules_list = include_once 'modules.php';
require_once APPLICATION_PATH . '/modules/Application/Loader/Modules.php';
$modules = new \Application\Loader\Modules();
$modules_config = $modules->modulesConfig($modules_list);

$config = array_merge_recursive($config, $modules_config);

return new \Phalcon\Config($config);
