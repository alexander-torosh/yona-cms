<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Domain\User\UseCase;

use Domain\User\Repository\UserRepository;

class UserPasswordCase
{
    private $repository;

    public function __construct() {
        $this->repository = new UserRepository();
    }
}