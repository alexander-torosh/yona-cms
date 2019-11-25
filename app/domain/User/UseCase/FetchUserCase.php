<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Domain\User\UseCase;

use Domain\User\Repository\UserRepository;

class FetchUserCase
{
    private $repository;

    public function __construct()
    {
        $this->repository = new UserRepository();
    }

    public function getUser(int $id)
    {
        return $this->repository->fetchUser($id);
    }
}