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
            $this->response->redirect('admin/index/login');
            return $this->response->send();
        }

        // Проверка пользователя yona
        $wezoom = AdminUser::findFirst("login = 'yona'");
        if ($wezoom) {
            $this->flash->warning("Обнаружен административный пользователь 'yona', для соблюдения мер безопасности, его необходимо Delete и создать новую личную учетную запись");
        }

        $changelog = file_get_contents(ROOT . '/../CHANGELOG.md');
        $this->view->changelog = nl2br(trim($changelog));

        $this->view->title = $this->helper->translate('Административная панель YonaCms');
        $this->helper->title()->append($this->helper->translate('Стартовая страница'));

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
                                $this->flash->success($this->helper->translate("Приветствуем в административной панели управления!"));
                                $this->response->redirect('admin');
                                return $this->response->send();
                            } else {
                                $this->flash->error($this->helper->translate("Пользователь не активирован"));
                            }
                        } else {
                            $this->flash->error($this->helper->translate("Неверный логин или пароль"));
                        }
                    } else {
                        $this->flash->error($this->helper->translate("Неверный логин или пароль"));
                    }
                } else {
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                }
            } else {
                $this->flash->error($this->helper->translate("Ошибка безопасности"));
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
                die("Ошибка безопасности");
            }
        } else {
            die("Ошибка безопасности");
        }
    }

}
