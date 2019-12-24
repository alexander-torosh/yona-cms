<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Core\Domain;

use JsonSerializable;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class PresenterEntity implements JsonSerializable
{
    public function jsonSerialize()
    {
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);

        return json_decode($serializer->serialize($this, 'json'));
    }
}
