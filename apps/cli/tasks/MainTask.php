<?php

class MainTask extends \Phalcon\Cli\Task
{
    public function mainAction(): void
    {
        echo 'This is the default task and the default action', PHP_EOL;
    }
}