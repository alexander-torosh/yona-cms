<?php

/**
 * AdminUserController
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Admin\Controller;

use Application\Mvc\Controller;
use Admin\Model\AdminUser;
use Admin\Form\LoginForm;
use Phalcon\Mvc\View;

class IndexController extends Controller
{

    public function indexAction()
    {
        $auth = $this->session->get('auth');
        if (!$auth || !isset($auth->admin_session) || !$auth->admin_session) {
            $this->flash->notice($this->helper->translate("Authenticate yourself, please"));
            $this->response->redirect('admin/index/login');
            return $this->response->send();
        }

        $this->view->setMainView(MAIN_VIEW_PATH . 'admin');

        $this->view->title = $this->helper->translate('Admin panel Start page');
        $this->helper->title()->append($this->helper->translate('Start page'));

        $this->helper->activeMenu()->setActive('admin-home');

    }

    public function loginAction()
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);

        /*$this->assets->collection('admin-login-css')
                ->addCss(__DIR__ . '/../assets/login.css')
                ->setLocal(true)
                ->addFilter(new \Phalcon\Assets\Filters\Cssmin())
                ->setTargetPath(PUBLIC_PATH . '/assets/admin-login.css')
                ->setTargetUri('assets/admin-login.css');*/

        $form = new LoginForm();

        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                $login    = $this->request->getPost('login', 'string');
                $password = $this->request->getPost('password', 'string');
                $user     = AdminUser::findFirst("login='$login'");
                if ($user) {
                    if ($user->checkPassword($password)) {
                        if ($user->isActive()) {
                            $this->session->set('auth', $user->getAuthData());
                            $this->flash->success($this->helper->translate("Wellcome to adminpanel"));
                            $this->response->redirect('admin');
                            return $this->response->send();
                        } else {
                            $this->flash->error($this->helper->translate("User isn't active"));
                        }
                    } else {
                        $this->flash->error($this->helper->translate("Wrong login/password"));
                    }
                } else {
                    $this->flash->error($this->helper->translate("user not found Wrong login/password"));
                }
            } else {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            }
        }

    }

    public function logoutAction()
    {
        $this->session->remove('auth');
        $this->response->redirect('');
        return $this->response->send();

    }

}
