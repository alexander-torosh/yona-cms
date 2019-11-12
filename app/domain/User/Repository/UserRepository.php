<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Domain\User\Repository;

use Domain\User\Entity\User;

class UserRepository
{
    public function fetchUserById(int $id): ?User
    {
        // @TODO Implement user fetching from cache and database
    }
}