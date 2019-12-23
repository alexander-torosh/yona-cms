<?php

namespace Domain\User;

use Core\Domain\DomainClientService;
use Domain\User\Exceptions\UserException;
use Domain\User\Factories\UserFactory;
use stdClass;

class UserClientService extends DomainClientService
{
    public function createUser(array $data = []): int
    {
        $db = $this->getDI()->get('db');
        $db->begin();

        try {
            $user = UserFactory::create($data);
            $repository = new UserRepository($this->getDI());

            $userID = $repository->insertUserIntoDb($user);
            $db->commit();

            $user->setUserId($userID);

            return $user->getUserID();
        } catch (UserException $e) {
            $db->rollback;
        }
    }

    public function retrieveUserObject(array $params = []): stdClass
    {
        $user = UserFactory::retrieve($params, $this->getDI());

        return $this->toJsonObject($user);
    }

    public function deleteUser(array $params = [])
    {
    }

    public function usersList(array $params = [])
    {
        // Build and return users list
    }
}
