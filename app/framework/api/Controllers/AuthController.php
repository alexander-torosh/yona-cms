<?php

namespace Api\Controllers;

use Api\ApiController;
use Domain\User\Services\UserAuthService;

class AuthController extends ApiController
{
    public function authenticate()
    {
        $data = $this->request->getJsonRawBody();

        $authService = new UserAuthService($this->getDI());
        $token = $authService->authenticate($data);

        return $this->json(['token' => $token]);
    }
}
