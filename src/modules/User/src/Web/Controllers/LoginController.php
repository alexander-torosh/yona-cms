<?php
/**
 * @author Oleksandr Torosh <webtorua@gmail.com>
 */

namespace User\Web\Controllers;

use Core\Controllers\ControllerAbstract;

class LoginController extends ControllerAbstract
{
    public function indexAction()
    {
        $this->assets->addJs('/dist/user.js');
    }
}