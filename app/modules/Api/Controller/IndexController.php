<?php

namespace Api\Controller;

/**
 * Class IndexController.
 *
 * @package Api\Controller
 */
class IndexController extends \Api\Controller\RestController
{
    /**
     * API start page.
     *
     * @throws \Api\Exception\NotImplementedException
     */
    public function indexAction()
    {
        $payload = new \Api\Model\Payload('Welcome to api for yona-cms!');

        $this->render($payload);
    }
}
