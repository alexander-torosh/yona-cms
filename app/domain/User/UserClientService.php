<?php

namespace Domain\User;

use Core\Domain\DomainClientService;
use Domain\User\Exceptions\UserException;
use Domain\User\Factories\UserFactory;
use Phalcon\Db\Adapter\Pdo\Postgresql;
use stdClass;

class UserClientService extends DomainClientService
{
    /**
     * @param stdClass $data
     * @return int
     * @throws UserException
     */
    public function createUser(stdClass $data): int
    {
        /** @var Postgresql */
        $db = $this->getDI()->get('db');
        $db->begin();

        try {
            $user = UserFactory::create($data);
            $repository = new UserRepository();

            $user = $repository->insertUserIntoDb($user);
            $db->commit();

            return $user->getId();

        } catch (UserException $e) {
            if ($db->isUnderTransaction()) {
                $db->rollback();
            }

            throw $e;
        }
    }

    /**
     * @param int $id
     * @return stdClass
     * @throws UserException
     */
    public function retrieveUserById(int $id): stdClass
    {
        $user = UserFactory::retrieveById($id);

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
