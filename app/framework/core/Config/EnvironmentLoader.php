<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Core\Config;

use josegonzalez\Dotenv\Loader;

class EnvironmentLoader
{
    const CACHE_FILEPATH = __DIR__ . '/../../../cache/app/env.php';

    /**
     * @param string $filepath
     * @param bool $caching
     */
    public function load(string $filepath, $caching = true)
    {
        // If caching enabled
        if ($caching) {
            $cachedConfiguration = $this->readConfigCache();
            if (empty($cachedConfiguration)) {
                $loader = $this->loadRootEnvFile($filepath);

                // Save cached file
                $this->saveCache($loader->toArray());

            } else {
                $this->putVariablesToEnv($cachedConfiguration);
            }
        } else {
            // If not enabled, just load root .env file
            $this->loadRootEnvFile($filepath);
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
     * @return array
     */
    private function readConfigCache(): array
    {
        if (is_file(self::CACHE_FILEPATH)) {
            $config = include(self::CACHE_FILEPATH);
            return $config;
        }
        return [];
    }

    /**
     * @param array $configArray
     */
    private function saveCache(array $configArray = [])
    {
        $configFileContents = '<?php' . PHP_EOL . PHP_EOL . 'return [' . PHP_EOL;
        foreach ($configArray as $index => $value) {
            $configFileContents .= '   "' . $index . '" => "' . $value . '",' . PHP_EOL;
        }
        $configFileContents .= '];';
        file_put_contents(self::CACHE_FILEPATH, $configFileContents);
    }

    private function putVariablesToEnv($cachedConfiguration)
    {
        foreach($cachedConfiguration as $index => $value) {
            putenv("$index=$value");
        }
    }
}