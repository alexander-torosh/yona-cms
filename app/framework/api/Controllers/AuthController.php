<?php

namespace Api\Controllers;

use Api\ApiController;
use Domain\User\Services\UserAuthService;

class AuthController extends ApiController
{
    public function authenticate()
    {
        $data = $this->request->getJsonRawBody();
        $service = new UserAuthService($this->getDI());

        $token = $service->authenticate($data);

        return $this->json(['token' => $token]);
    }
}
