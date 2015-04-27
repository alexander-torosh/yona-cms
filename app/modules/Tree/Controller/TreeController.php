<?php
/**
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.net)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Tree\Controller;

use Application\Mvc\Controller;

class TreeController extends Controller
{

    public function indexAction()
    {
        $this->setAdminEnvironment();
    }

}