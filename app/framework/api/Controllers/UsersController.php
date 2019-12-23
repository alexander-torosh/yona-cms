<?php

namespace Api\Controllers;

use Api\ApiController;
use Domain\User\UserClientService;

class UsersController extends ApiController
{
    public function create()
    {
        $data = $this->request->getJsonRawBody(true);

        $service = new UserClientService($this->getDi());
        $userID = $service->createUser($data);

        $this->response->setStatusCode(201);

        return $this->json(['userID' => $userID]);
    }

    public function retrieve($userID)
    {
        $service = new UserClientService($this->getDi());
        $user = $service->retrieveUserObject(['userID' => $userID]);

        return $this->json(['user' => $user]);
    }
}
