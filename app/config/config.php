<?php

$application = include_once APPLICATION_ENV . '/application.php';

/**
 * ============================================================================
 * Configuration
 */
$config = array(
    'loader' => array(
        'namespaces' => array(

            // Vendors:
            // 'Zend' => APPLICATION_PATH . '/../vendor/zendframework/zendframework/library/Zend', // for example

        ),
    ),
    'database' => $application['database'],
    'profiler' => $application['profiler'],
    'cache' => $application['cache'],
    'metadata_cache' => $application['metadata_cache'],
);
/**
 * ============================================================================
 */


// Формирование конфигурационного списка модулей
$modules_list = include_once 'modules.php';
require_once APPLICATION_PATH . '/modules/Application/Loader/Modules.php';
$modules = new \Application\Loader\Modules();
$modules_config = $modules->modulesConfig($modules_list);

$config = array_merge($config, $modules_config);

return new \Phalcon\Config($config);
