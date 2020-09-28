<?php

namespace Api\Controllers;

use Api\ApiController;
use Domain\User\UserClientService;

class UsersController extends ApiController
{
    public function create()
    {
        $service = new UserClientService();
        $service->setDI($this->getDI());

        $data = $this->request->getJsonRawBody();
        $id = $service->createUser($data);

        $this->response->setStatusCode(201);

        return $this->json(['id' => $id]);
    }

    public function retrieveById($id)
    {
        $service = new UserClientService();
        $service->setDI($this->getDI());

        $id = (int) $id;
        $user = $service->retrieveUserById($id);

        return $this->json(['user' => $user]);
    }

    public function usersList()
    {
        return $this->json([]);
    }
}
