<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Core\Config;

use josegonzalez\Dotenv\Loader;
use Phalcon\Di\AbstractInjectionAware;

class EnvironmentLoader extends AbstractInjectionAware
{
    const CACHE_KEY = 'dontev-configuration';

    public function load()
    {
        // .env filepath
        $filepath = __DIR__ . '/../../../../.env';

        $cachedConfiguration = $this->readConfigCache();
        if (!$cachedConfiguration) {
            $loader = $this->loadRootEnvFile($filepath);

            // Save configuration to cache if APP_ENV is not 'development'
            if (getenv('APP_ENV') !== 'development') {

                // Save configuration to cache
                $this->saveConfigCache($loader->toArray());
            }

        } else {
            $this->putVariablesToEnv($cachedConfiguration);
        }
    }

    /**
     * @param string $filepath
     * @return Loader
     */
    private function loadRootEnvFile(string $filepath): Loader
    {
        // Use Dotenv Loader
        $loader = new Loader($filepath);
        $loader->parse();
        $loader->putenv();

        return $loader;
    }

    /**
     * @return \stdClass
     */
    private function readConfigCache(): ?\stdClass
    {
        $apcuCache = $this->getDI()->get('serverCache');
        $config    = $apcuCache->get(self::CACHE_KEY);
        if ($config) {
            return $config;
        }

        // Default return
        return null;
    }

    /**
     * @param array $configArray
     */
    private function saveConfigCache(array $configArray = [])
    {
        $apcuCache = $this->getDI()->get('serverCache');
        $apcuCache->set(self::CACHE_KEY, $configArray);
    }

    /**
     * @param \stdClass $cachedConfiguration
     */
    private function putVariablesToEnv(\stdClass $cachedConfiguration)
    {
        foreach ($cachedConfiguration as $index => $value) {
            putenv("$index=$value");
        }
    }
}