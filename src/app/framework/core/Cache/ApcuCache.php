<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Core\Cache;

use Phalcon\Cache;
use Phalcon\Cache\AdapterFactory;
use Phalcon\Storage\SerializerFactory;

class ApcuCache
{
    const DEFAULT_LIFETIME = 30;

    public function init(): Cache
    {
        $serializerFactory = new SerializerFactory();
        $adapterFactory = new AdapterFactory($serializerFactory);

        $options = [
            'defaultSerializer' => 'Json',
            'lifetime' => self::DEFAULT_LIFETIME,
        ];

        $adapter = $adapterFactory->newInstance('apcu', $options);

        return new Cache($adapter);
    }
}
