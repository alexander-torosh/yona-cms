<?php

namespace Core;

use Phalcon\Cli\Task;

class BaseTask extends Task
{
    private $startExecute;

    public function initialize(): void
    {
        $this->startExecute = microtime(true);
    }

    public function reportResources(): void
    {
        echo '************************************', PHP_EOL;
        echo 'Execution time <' . (microtime(true) - $this->startExecute) . '>', PHP_EOL;
        echo 'Memory peak:<' . (memory_get_peak_usage()) . '>', PHP_EOL;
        echo '************************************', PHP_EOL, PHP_EOL;
    }
}
