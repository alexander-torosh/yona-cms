<?php

namespace Domain\User;

use Core\Domain\DomainClientService;
use Domain\User\Exceptions\UserException;
use Domain\User\Factories\UserFactory;
use Phalcon\Db\Adapter\Pdo\Postgresql;
use stdClass;

class UserClientService extends DomainClientService
{
    public function createUser(stdClass $data): int
    {
        /** @var Postgresql */
        $db = $this->getDI()->get('db');
        $db->begin();

        try {
            $user = UserFactory::create($data);
            $repository = new UserRepository();

            $id = $repository->insertUserIntoDb($user);
            $db->commit();

            $user->setId($id);

            return $user->getId();
        } catch (UserException $e) {
            if ($db->isUnderTransaction()) {
                $db->rollback();
            }

            throw $e;
        }
    }

    public function retrieveUserObject(array $params = []): stdClass
    {
        $user = UserFactory::retrieve($params);

        return UserPresenter::singleUserObject($user);
    }

    public function deleteUser(array $params = [])
    {
    }

    public function usersList(array $params = [])
    {
        // Build and return users list
    }
}
