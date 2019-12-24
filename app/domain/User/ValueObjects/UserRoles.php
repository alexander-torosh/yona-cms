<?php

namespace Domain\User\ValueObjects;

class UserRoles
{
    const ROLES = [
        'member',
        'editor',
        'admin',
    ];

    const DEFAULT_ROLE = self::ROLES[0];
}
