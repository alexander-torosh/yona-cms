<?php

namespace Domain\User;

use stdClass;

class UserPresenter
{
    public static function singleUserObject(User $user): stdClass
    {
        $object = new stdClass();

        $object->id = $user->getId();
        $object->email = $user->getEmail();
        $object->name = $user->getName();
        $object->role = $user->getRole();

        return $object;
    }
}
