<?php

namespace Gaw508\Config;

use Gaw508\Config\Exception\ConfigException;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Config
 *
 * @author George Webb <george@webb.uno>
 * @package Gaw508\Config\Exception
 */
class Config
{
    /**
     * Stores all of the configuration key value pairs.
     *
     * @var array
     */
    private static $config = array();

    /**
     * Get a config value with specified key
     *
     * @param $key string       The key of the config value
     * @return mixed            The config value
     * @throws ConfigException  Throws a config exception if the value doesn't exist
     */
    public static function get($key)
    {
        if (!isset(static::$config[$key])) {
            throw new ConfigException('Missing configuration value: ' . $key);
        }
        return static::$config[$key];
    }

    /**
     * Set a config value.
     *
     * @param $key string   The key of the config value
     * @param $value mixed  The config value or array of values
     * @return void
     */
    public static function set($key, $value)
    {
        static::$config[$key] = $value;
    }

    /**
     * Load a config array
     *
     * @param array $config         An array of config values
     * @throws ConfigException      If config class for environment doesn't exist
     * @return void
     */
    public static function load($config)
    {
        foreach ($config as $key => $value) {
            static::set($key, $value);
        }
    }

    /**
     * Load a yaml config file
     *
     * @param string $path          The path to the .yml file
     * @throws ConfigException      If file doesn't exist
     * @return void
     */
    public static function loadYaml($path)
    {
        if (!file_exists($path)) {
            throw new ConfigException('Config file does not exist: ' . $path);
        }

        $config = Yaml::parse(file_get_contents($path));

        static::load($config);
    }

    /**
     * Loads all yaml config files within a directory
     *
     * @param string $path      The path of the directory
     * @throws ConfigException  If file doesn't exist
     * @return void
     */
    public static function loadDirectory($path)
    {
        foreach (scandir($path) as $file) {
            if (pathinfo($file)['extension'] == 'yml') {
                Config::loadYaml(__DIR__ . '/autoload/' . $file);
            }
        }
    }

    /**
     * Clears all config values
     *
     * @return void
     */
    public static function clearAll()
    {
        static::$config = array();
    }
}
