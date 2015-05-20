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
        $this->setAdminEnvironment();
        $this->view->languages_disabled = true;

        $auth = $this->session->get('auth');
        if (!$auth || !isset($auth->admin_session) || !$auth->admin_session) {
            $this->flash->notice($this->helper->translate("Авторизируйтесь пожалуйста"));
            return $this->redirect('admin/index/login');
        }

        // Проверка пользователя yona
        $yona = AdminUser::findFirst("login = 'yona'");
        if ($yona) {
            $this->flash->warning("Found the administrative user 'yona', to comply with security measures, it is necessary to Delete and create a new personal account");
        }

        $changelog = file_get_contents(ROOT . '/../CHANGELOG.md');
        $this->view->changelog = nl2br(trim($changelog));

        $this->view->title = $this->helper->translate('YonaCms Admin Panel');
        $this->helper->title()->append($this->helper->translate('Home'));

        $this->helper->activeMenu()->setActive('admin-home');

    }

    public function loginAction()
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);

        $form = new LoginForm();

        if ($this->request->isPost()) {
            if ($this->security->checkToken()) {
                if ($form->isValid($this->request->getPost())) {
                    $login = $this->request->getPost('login', 'string');
                    $password = $this->request->getPost('password', 'string');
                    $user = AdminUser::findFirst("login='$login'");
                    if ($user) {
                        if ($user->checkPassword($password)) {
                            if ($user->isActive()) {
                                $this->session->set('auth', $user->getAuthData());
                                $this->flash->success($this->helper->translate("Welcome to the administrative control panel!"));
                                $this->response->redirect('admin');
                                return $this->response->send();
                            } else {
                                $this->flash->error($this->helper->translate("User is not activated yet"));
                            }
                        } else {
                            $this->flash->error($this->helper->translate("Incorrect login or password"));
                        }
                    } else {
                        $this->flash->error($this->helper->translate("Incorrect login or password"));
                    }
                } else {
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                }
            } else {
                $this->flash->error($this->helper->translate("Security errors"));
            }
        }

        $this->view->form = $form;

    }

    public function logoutAction()
    {
        if ($this->request->isPost()) {
            if ($this->security->checkToken()) {
                $this->session->remove('auth');
                $this->response->redirect('');
                return $this->response->send();
            } else {
                die("Security errors");
            }
        } else {
            die("Security errors");
        }
    }

}
