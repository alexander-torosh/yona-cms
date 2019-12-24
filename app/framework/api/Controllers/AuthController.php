<?php

namespace Api\Controllers;

use Api\ApiController;

class AuthController extends ApiController
{
    public function authenticate()
    {
        $data = $this->request->getJsonRawBody();
    }
}
