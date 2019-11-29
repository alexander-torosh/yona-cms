<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Core\Assets;

use Core\Exceptions\Assets\BuildEntrypointsFileNotFound;
use Phalcon\Cache;
use Phalcon\Di\AbstractInjectionAware;

class AssetsHelper extends AbstractInjectionAware
{
    const CACHE_KEY = 'assets-manifest';
    const CACHE_LIFETIME = 30;
    const MANIFEST_FILEPATH = __DIR__.'/../../../../public/build/manifest.json';

    private $manifest;

    public function __construct($container)
    {
        $this->setDI($container);
        $this->init();
    }

    public function getUrl($file = 'build/front.js')
    {
        if (isset($this->manifest->{$file})) {
            $url = $this->getDI()->get('url');

            return $url->get($this->manifest->{$file});
        }
    }

    /**
     * AssetsManager constructor.
     *
     * @throws BuildEntrypointsFileNotFound
     * @throws Cache\Exception\InvalidArgumentException
     */
    private function init()
    {
        // @var $serverCache Cache
        $serverCache = $this->getDI()->get('serverCache');
        $cachedContents = $serverCache->get(self::CACHE_KEY);
        if (!$cachedContents) {
            // Read manifest.json file
            $manifestContents = file_get_contents(self::MANIFEST_FILEPATH);
            if (!$manifestContents) {
                throw new BuildEntrypointsFileNotFound('Assets manifest.json file not found. Please, build frontend assets first.');
            }

            // Decode JSON contents to stdClass
            $contentsObject = json_decode($manifestContents);
            $this->manifest = $contentsObject;

            // Save it to serverCache
            $serverCache->set(self::CACHE_KEY, json_encode($this->manifest), self::CACHE_LIFETIME);
        } else {
            $this->manifest = json_decode($cachedContents);
        }
    }
}
