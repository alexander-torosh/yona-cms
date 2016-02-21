<?php

/**
 * @author Oleksandr Torosh <webtorua@gmail.com>
 */
namespace Yona;

class Application extends \Phalcon\Mvc\Application
{

    public function run()
    {
        $dotenv = new \Dotenv\Dotenv(BASE_PATH . '/app/config/');
        $dotenv->load();
        //$dotenv->required(BASE_PATH . '/app/config/.env_required.php');

        $config = include_once BASE_PATH . '/app/config/services.php';

        $di       = new \Phalcon\DI\FactoryDefault();
        $serviceLoader = new ServiceLoader($config, $di, ['web']);
        $di->setShared('serviceLoader', $serviceLoader);

        // Error Handling
        //$di->get('eventsManager')->attach('dispatch', $di->get('errorHandler'));

        // Acl
        $di->get('eventsManager')->attach('dispatch', $di->get('acl'));

        // Tag
        \Phalcon\Tag::setTitleSeparator(" - ");
        \Phalcon\Tag::setTitle("Yona CMS");

        // Modules Namespaces
        $loader = $di->get('loader');
        $namespaces = $di->get('modulesLoader')->getNamespaces();
        $loader->registerNamespaces($namespaces);
        $loader->register();

        $this->setDI($di);

        // Register Modules
        $this->registerModules($di->get('modulesLoader')->getModules());

        echo $this->handle()->getContent();
    }

}