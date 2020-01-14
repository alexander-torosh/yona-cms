<?php

namespace Domain\User\Services;

use Core\JsonWebToken\JsonWebTokenService;
use Domain\User\Exceptions\UserException;
use Domain\User\Factories\UserFactory;
use Domain\User\User;
use Domain\User\UserFilter;
use Phalcon\Di\AbstractInjectionAware;
use Phalcon\Di\DiInterface;
use stdClass;

class UserAuthService extends AbstractInjectionAware
{
    public function __construct(DiInterface $di)
    {
        $this->setDi($di);
    }

    public function authenticate(stdClass $data): string
    {
        $userFilter = new UserFilter();
        $params = $userFilter->sanitizeAuthParams($data);

        $user = UserFactory::retrieveByEmail($params->email);
        if ($user->doesPasswordMatch($data->password)) {
            return $this->createJsonWebToken($user, $params->rememberMe);
        }

        throw new UserException('Wrong email/password combination. Authentication failed.');
    }

    private function createJsonWebToken(User $user, $rememberMe = false): string
    {
        $expiresIn = '1 day';

        if (true === $rememberMe) {
            $expiresIn = '14 days';
        }

        return JsonWebTokenService::createToken([
            'sub' => 'AuthToken',
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'name' => $user->getName(),
                'role' => $user->getRole(),
            ],
        ], $expiresIn);
    }
}
