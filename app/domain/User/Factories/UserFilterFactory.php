<?php

namespace Domain\User\Factories;

use Core\Domain\DomainFilterFactory;
use stdClass;

class UserFilterFactory extends DomainFilterFactory
{
    public static function sanitizeCreationData(stdClass $data): stdClass
    {
        $locator = self::getFilterLocator();

        $result = new stdClass();
        $result->email = '';
        $result->name = '';
        $result->role = 'member';
        $result->password = '';

        if (isset($data->email)) {
            $result->email = $data->email ? $locator->sanitize($data->email, 'email') : '';
        }
        if (isset($data->name)) {
            $result->name = $data->name ? $locator->sanitize($data->name, 'strip_tags') : '';
        }
        if (isset($data->role)) {
            $result->role = $data->role ? $locator->sanitize($data->role, 'strip_tags') : 'member';
        }
        if (isset($data->password)) {
            $result->password = $data->password ? $data->password : '';
        }

        return $result;
    }
}
