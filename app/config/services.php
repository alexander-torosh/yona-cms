<?php
/**
 * DEV VERSION
 * Dependency Injection/Service Location
 * Complex Registration of Services
 * https://docs.phalconphp.com/en/latest/reference/di.html#complex-registration
 *
 * Used associative array for services definition
 *
 * @author Oleksandr Torosh <webtorua@gmail.com>
 */

return [
    // Application
    'base_uri'      => getenv('BASE_URI'),

    // Environment
    'web'           => [
        'default'  => getenv('APP_ENV'),
        'adapters' => [
            'development' => function (\Phalcon\DI\FactoryDefault $di) {
                error_reporting(E_ALL);
                ini_set('display_errors', 1);
                ini_set('display_startup_errors', 1);

                $di->get('debug')->listen();

                $di->get('db')->setEventsManager($di->get('eventsManager'));

                $di->get('eventsManager')->attach('db', function ($event, $db) use ($di) {
                    $profiler = $di->getShared('profiler');
                    if ($event->getType() == 'beforeQuery') {
                        $profiler->startProfile($db->getSQLStatement());
                    }
                    if ($event->getType() == 'afterQuery') {
                        $profiler->stopProfile();
                    }
                });
            },
            'production'  => function () {
                ini_set('display_errors', 0);
                ini_set('display_startup_errors', 0);
            },
        ],
    ],

    // Main Services
    'modulesLoader' => '\Yona\ModulesLoader',
    'loader'        => '\Phalcon\Loader',
    'eventsManager' => '\Phalcon\Events\Manager',
    'dispatcher'    => [
        'className' => '\Phalcon\Mvc\Dispatcher',
        'calls'     => [
            [
                'method'    => 'setEventsManager',
                'arguments' => [
                    [
                        'type' => 'service',
                        'name' => 'eventsManager',
                    ],
                ],
            ],
        ],
    ],
    'db'            => [
        'className' => '\Phalcon\Db\Adapter\Pdo\Mysql',
        'arguments' => [
            'arguments' => [
                'type'  => 'parameter',
                'value' => [
                    getenv('DATABASE_CONNECT_TYPE') => getenv('DATABASE_CONNECT_POINT'),
                    'dbname'                        => getenv('DATABASE_NAME'),
                    'username'                      => getenv('DATABASE_USER'),
                    'password'                      => getenv('DATABASE_PASS'),
                ],
            ],
        ],
    ],
    'view'          => '\Yona\View\View',
    'acl'           => '\Yona\Plugin\AclPlugin',
    'session'       => [
        'default'  => getenv('SESSION_ADAPTER'),
        'adapters' => [
            'files' => [
                'className' => '\Phalcon\Session\Adapter\Files',
                'calls'     => [
                    [
                        'method' => 'start',
                    ],
                ],
            ],
            'redis' => [
                'className' => '\Phalcon\Session\Adapter\Redis',
                'arguments' => [
                    [
                        'type'  => 'parameter',
                        'value' => [
                            'host'       => getenv('REDIS_HOST'),
                            'port'       => getenv('REDIS_PORT'),
                            'persistent' => getenv('REDIS_PERSISTENT'),
                            'lifetime'   => getenv('REDIS_LIFETIME'),
                            'prefix'     => getenv('REDIS_PREFIX'),
                            'uniqueId'   => getenv('REDIS_UNIQUE_ID'),
                        ],
                    ],
                ],
                'calls'     => [
                    [
                        'method' => 'start',
                    ],
                ],
            ],
        ],
    ],
    'cache'         => [
        'default'  => getenv('CACHE_ADAPTER'),
        'adapters' => [
            'files'     => [
                'className' => 'Phalcon\Cache\Backend\File',
                'arguments' => [
                    [
                        'type'      => 'instance',
                        'className' => '\Phalcon\Cache\Frontend\Data',
                        'arguments' => [
                            [
                                'type'  => 'parameter',
                                'value' => [
                                    "lifetime" => 60,
                                    "prefix"   => 'yona',
                                ],
                            ],
                        ],
                    ],
                    [
                        'type'  => 'parameter',
                        'value' => [
                            "cacheDir" => __DIR__ . '/../../data/cache/backend/',
                        ],
                    ],
                ],
            ],
            'memcached' => [
                'className' => 'Phalcon\Cache\Backend\Libmemcached',
                'arguments' => [
                    [
                        'type'      => 'instance',
                        'className' => '\Phalcon\Cache\Frontend\Data',
                        'arguments' => [
                            "servers" => [
                                [
                                    "host"   => getenv('MEMCACHE_HOST'),
                                    "port"   => getenv('MEMCACHE_PORT'),
                                    "weight" => "1",
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'modelsCache'   => ['type' => 'service', 'name' => 'cache'],
    'crypt'         => [
        'className' => '\Phalcon\Crypt',
        'calls'     => [
            [
                'method'    => 'setKey',
                'arguments' => [
                    ['type' => 'parameter', 'value' => getenv('CRYPT_KEY')],
                ],
            ],
        ],
    ],
    'debug'         => '\Phalcon\Debug',
    'profiler'      => '\Phalcon\Db\Profiler',
    'userSession'   => [
        'className' => '\Phalcon\Session\Bag',
        'arguments' => [
            ['type' => 'parameter', 'value' => 'user'],
        ],
    ],
    'router' => '\Yona\Router',

    // Application Helpers
    'helper'        => '\Yona\Application\Helper',

    // Custom services

];