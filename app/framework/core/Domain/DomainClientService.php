<?php

namespace Core\Domain;

use Phalcon\Di\AbstractInjectionAware;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class DomainClientService extends AbstractInjectionAware
{
    protected function toJsonObject($object)
    {
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);

        return json_decode($serializer->serialize($object, 'json'));
    }
}
