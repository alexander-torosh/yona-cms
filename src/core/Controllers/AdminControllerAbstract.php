<?php
/**
 * @author Oleksandr Torosh <webtorua@gmail.com>
 */

namespace Core\Controllers;

class AdminControllerAbstract extends ControllerAbstract
{
    public function initialize()
    {
        $this->getDI()->get('view')->setMainView(VIEW_PATH . 'admin');
    }
}