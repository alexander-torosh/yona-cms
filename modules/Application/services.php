<?php

/** @var array */
$config = \Phalcon\Di::getDefault()->get('appConfig');

return [
    // Phalcon Services
    'loader'        => \Phalcon\Loader::class,
    'eventsManager' => \Phalcon\Events\Manager::class,

    'view' => function($di) {
        $view = new \Core\View\View();
        return $view->register($di);
    },

    // Global configuration defines
    'base_uri'         => BASE_URI,

    'url' => ['className' => \Phalcon\Mvc\Url::class,
        'arguments' => [
            ['type' => 'parameter', 'value' => null],
        ],
        'calls'     => [
            [
                'method'    => 'setBaseUri',
                'arguments' => [
                    ['type' => 'parameter', 'value' => '/'],
                ],
            ]
        ],
    ],

    'flash' => ['className' => \Phalcon\Flash\Session::class,
        'arguments' => [],
    ],

    'db'               => [
        'className' => \Phalcon\Db\Adapter\Pdo\Mysql::class,
        'arguments' => [
            'arguments' => [
                'type'  => 'parameter',
                'value' => [
                    'host'      => $config['MYSQL_HOST'],
                    'dbname'    => $config['MYSQL_DATABASE'],
                    'username'  => $config['MYSQL_USER'],
                    'password'  => $config['MYSQL_PASSWORD'],
                    'port'      => $config['MYSQL_PORT'],
                    'charset'   => $config['MYSQL_CHARSET'],
                ],
            ],
        ],
    ],

    'session'          => [
        'default'  => $config['SESSION_ADAPTER'],
        'adapters' => [
            'files' => [
                'className' => \Phalcon\Session\Adapter\Files::class,
                'calls'     => [
                    [
                        'method' => 'start',
                    ],
                ],
            ],
            'redis' => [
                'className' => \Phalcon\Session\Adapter\Redis::class,
                'arguments' => [
                    [
                        'type'  => 'parameter',
                        'value' => [
                            'host'       => $config['REDIS_HOST'],
                            'port'       => $config['REDIS_PORT'],
                            'persistent' => $config['REDIS_PERSISTENT'],
                            'lifetime'   => $config['REDIS_LIFETIME'],
                            'prefix'     => $config['REDIS_PREFIX'],
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

    'cache'       => [
        'default'  => $config['CACHE_ADAPTER'],
        'adapters' => [
            'files'     => [
                'className' => Phalcon\Cache\Backend\File::class,
                'arguments' => [
                    [
                        'type'      => 'instance',
                        'className' => \Phalcon\Cache\Frontend\Data::class,
                        'arguments' => [
                            [
                                'type'  => 'parameter',
                                'value' => [
                                    'lifetime' => 60,
                                    'prefix'   => 'Core',
                                ],
                            ],
                        ],
                    ],
                    [
                        'type'  => 'parameter',
                        'value' => [
                            'cacheDir' => BASE_PATH . '/data/cache',
                        ],
                    ],
                ],
            ],
            'redis' => [
                'className' => \Phalcon\Cache\Backend\Redis::class,
                'arguments' => [
                    [
                        'type'      => 'instance',
                        'className' => \Phalcon\Cache\Frontend\Data::class
                    ],
                    [
                        'type'  => 'parameter',
                        'value' => [
                            'host'       => $config['REDIS_HOST'],
                            'port'       => $config['REDIS_PORT'],
                            'lifetime'   => $config['REDIS_LIFETIME'],
                        ],
                    ],
                ],
            ],
        ],
    ],
    // Redis
    'redis'       => [
        'className' => \Phalcon\Cache\Backend\Redis::class,
        'arguments' => [
            [
                'type'      => 'instance',
                'className' => \Phalcon\Cache\Frontend\Data::class,
            ],
            [
                'type'  => 'parameter',
                'value' => [
                    'host'       => $config['REDIS_HOST'],
                    'port'       => $config['REDIS_PORT'],
                    'lifetime'   => $config['REDIS_LIFETIME'],
                ],
            ],
        ],
    ],

    // Cookie
    'cookies'     => [
        'className' => \Phalcon\Http\Response\Cookies::class,
        'calls'     => [
            [
                'method'    => 'useEncryption',
                'arguments' => [
                    ['type' => 'parameter', 'value' => false],
                ],
            ],
        ],
    ],
];
