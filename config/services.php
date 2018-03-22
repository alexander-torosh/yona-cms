<?php

$config = require BASE_PATH.'config/config.php';

return [
    // Phalcon Services
    'loader'        => \Phalcon\Loader::class,
    'eventsManager' => \Phalcon\Events\Manager::class,
    'appConfig'     => [
        'className' => \Phalcon\Config::class,
        'arguments' => [
            ['type' => 'parameter', 'value' => $config],
        ],
    ],

    'view' => function($di) {
        $view = new \Core\View\View();
        return $view->register($di);
    },

    // Global configuration defines
    'base_uri'         => WEB_PATH,

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
        'arguments' => [
            ['type' => 'parameter', 'value' => [
                'error'   => 'alert alert-danger',
                'success' => 'alert alert-success',
                'notice'  => 'alert alert-info',
                'warning' => 'alert alert-warning',
            ]],
        ],
    ],

    'db'               => [
        'className' => \Phalcon\Db\Adapter\Pdo\Mysql::class,
        'arguments' => [
            'arguments' => [
                'type'  => 'parameter',
                'value' => [
                    'host'      => $config['db']['host'],
                    'dbname'    => $config['db']['dbname'],
                    'username'  => $config['db']['username'],
                    'password'  => $config['db']['password'],
                    'port'      => $config['db']['port'],
                    'charset'   => $config['db']['charset'],
                ],
            ],
        ],
    ],

    'session'          => [
        'default'  => $config['defaults']['session_adapter'],
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
                            'host'       => $config['redis']['host'],
                            'port'       => $config['redis']['port'],
                            'persistent' => $config['redis']['persistent'],
                            'lifetime'   => $config['redis']['lifetime'],
                            'prefix'     => $config['redis']['prefix'],
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
        'default'  => $config['defaults']['cache_adapter'],
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
                            'host'   => $config['redis']['host'],
                            'port'   => $config['redis']['port'],
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
                    'host' => $config['redis']['host'],
                    'port' => $config['redis']['port'],
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
