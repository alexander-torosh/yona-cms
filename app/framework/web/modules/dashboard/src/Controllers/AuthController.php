<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Dashboard\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Tag;

class AuthController extends Controller
{
    /**
     * @Access('guest')
     */
    public function loginAction()
    {
        Tag::prependTitle('Dashboard Login');

        $redirect = $this->request->getQuery('redirect');
        $sanitizedRedirect = trim(strip_tags($redirect));

        $this->view->setVars([
            'hideDashboardRoot' => true,
            'redirect' => $sanitizedRedirect
        ]);
    }
}