<?php

/**
 * DbProfilerPlugin
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

//use Phalcon\Mvc\Dispatcher;

class DbProfilerPlugin
{

    protected $_profiler;
    protected $_logger;

    public function __construct()
    {
        $this->_profiler = new \Phalcon\Db\Profiler();

    }

    public function beforeQuery($event, $connection)
    {
        $this->_profiler->startProfile($connection->getSQLStatement());

    }

    public function afterQuery($event, $connection)
    {
        $this->_profiler->stopProfile();

    }

    public function getProfiler()
    {
        return $this->_profiler;

    }

}
