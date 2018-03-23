<?php

namespace Cli;

class CliKernel extends \Phalcon\Cli\Console
{
    public function run(array $argv): void
    {
        // Service loader
        $config = include BASE_PATH . '/config/services.php';
        $di = new \Phalcon\DI\FactoryDefault\Cli();
        $serviceLoader = new \Core\Service\LoaderService($config, $di);
        $di->set('serviceLoader', $serviceLoader, true);

        $loader = $di->get('loader');
        $loader->registerDirs(
            [
                __DIR__ . '/tasks',
            ]
        );

        $loader->register();

        // Set Di
        $this->setDI($di);

        /**
         * Process the console arguments
         */
        $arguments = [];

        foreach ($argv as $k => $arg) {
            if ($k === 1) {
                $arguments['task'] = $arg;
            } elseif ($k === 2) {
                $arguments['action'] = $arg;
            } elseif ($k >= 3) {
                $arguments['params'][] = $arg;
            }
        }

        try {
            // Handle incoming arguments
            $this->handle($arguments);
        } catch (\Throwable $throwable) {
            fwrite(STDERR, $throwable->getMessage() . PHP_EOL);
            exit(1);
        }

    }
}