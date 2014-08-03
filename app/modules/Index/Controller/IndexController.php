<?php

namespace Index\Controller;

use Application\Mvc\Controller;
use Page\Model\Page;
use Phalcon\Exception;

class IndexController extends Controller
{

    public function indexAction()
    {
        $this->view->bodyClass = 'home';

        $page = Page::findFirst("slug = 'index'");
        if (!$page) {
            throw new Exception("Page 'index' not found");
            return;
        }
        $this->helper->title()->append($page->getMetaTitle());
        $this->helper->meta()->set('description', $page->getMetaDescription());
        $this->helper->meta()->set('keywords', $page->getMetaKeywords());
        $this->view->page = $page;

    }

    public function contactsAction()
    {
        $page = Page::findFirst("slug = 'contacts'");
        if (!$page) {
            throw new Exception("Page 'contacts' not found");
            return;
        }
        $this->helper->title()->append($page->getMetaTitle());
        $this->helper->meta()->set('description', $page->getMetaDescription());
        $this->helper->meta()->set('keywords', $page->getMetaKeywords());
        $this->view->page = $page;
    }

    public function callbackAction()
    {
        if ($this->request->isAjax() && $this->request->isPost()) {
            $response = new \stdClass();

            $post = $this->request->getPost(null, 'string');

            if (!$post['name'] || !$post['email'] || !$post['phone']) {
                $response->error = "Заполните все обязательные поля";
            } else {
                $messageBody = $this->getCallbackMessageBody($post);
                if ($this->sendMail($messageBody, 'Форма обратной связи reynaers.kiev.ua')) {
                    $response->success = true;
                    $response->successMsg = 'Спасибо! Ваше сообщение отправлено. Мы свяжемся с Вами в ближайшее время';
                } else {
                    $response->error = "Ошибка отправки сообщения";
                }
            }

            $this->returnJSON($response);
        }
    }

    private function sendMail($messageBody, $subject)
    {
        $message = new \Zend\Mail\Message();
        $message->setEncoding('utf-8');
        $message->setBody($messageBody);
        $message->addTo('info@reynaers.kiev.ua');
        $message->setSubject($subject);
        $message->setFrom('noreply@wezoom.net');

        $transport = new \Zend\Mail\Transport\Sendmail();

        if (APPLICATION_ENV == 'production') {
            $transport->send($message);
        }

        return true;

    }

    private function getCallbackMessageBody($post)
    {
        $result = "Контактные данные:" . PHP_EOL . PHP_EOL;
        $result .= "Имя: {$post['name']}" . PHP_EOL;
        $result .= "Тел.: {$post['phone']}" . PHP_EOL;
        $result .= "Email: {$post['email']}" . PHP_EOL . PHP_EOL;
        $result .= "Сообщение: {$post['message']}" . PHP_EOL;
        return $result;

    }

}
