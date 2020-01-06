<?php

namespace Api\Controllers;

use Api\ApiController;
use Domain\User\UserClientService;

class UsersController extends ApiController
{
    public function create()
    {
        $data = $this->request->getJsonRawBody();

        $service = new UserClientService($this->getDi());
        $id = $service->createUser($data);

        $this->response->setStatusCode(201);

        return $this->json(['id' => $id]);
    }

    public function retrieveById($id)
    {
        $id = (int) $id;
        $service = new UserClientService($this->getDi());
        $user = $service->retrieveUserById($id);

        return $this->json(['user' => $user]);
    }

    public function usersList()
    {
        return $this->json([]);
    }
}
