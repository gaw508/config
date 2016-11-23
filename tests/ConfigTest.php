<?php

namespace Gaw508\Config;

use Gaw508\Config\Exception\ConfigException;
use PHPUnit_Framework_TestCase;

/**
 * Class ConfigTest
 *
 * @author George Webb <george@webb.uno>
 * @package Gaw508\Config
 */
class ConfigTest extends PHPUnit_Framework_TestCase
{
    /**
     * Tear down after every test
     */
    public function tearDown()
    {
        Config::clearAll();
    }

    /**
     * Test the get and set functions
     */
    public function testGetSet()
    {
        $expected = array(
            'string' => 'testing string',
            'string_empty' => '',
            'int' => 1,
            'int0' => 0,
            'array' => array(1, 2, 3),
            'array_empty' => array(),
            'bool_true' => true,
            'bool_false' => false,
            'object' => new \stdClass()
        );

        foreach ($expected as $key => $value) {
            Config::set($key, $value);
            $actual = Config::get($key);
            $this->assertEquals($value, $actual);
        }
    }

    /**
     * Test that overriding a value works
     */
    public function testOverridingValues()
    {
        Config::set('one', 1);
        $this->assertEquals(1, Config::get('one'));

        Config::set('one', 2);
        $this->assertEquals(2, Config::get('one'));
    }

    /**
     * Tests that the get method throws an exception when a config value doesn't exist.
     */
    public function testGetThrowsException()
    {
        $this->setExpectedException('Gaw508\Config\Exception\ConfigException');
        Config::get(md5(time()));
    }

    /**
     * Test that set ignores null values
     *
     * @throws ConfigException
     */
    public function testSetIgnoresNullValues()
    {
        $this->setExpectedException('Gaw508\Config\Exception\ConfigException');
        Config::set('myNullValue', null);
        Config::get('myNullValue');
    }

    /**
     * Tests that the load function loads in config values
     */
    public function testLoad()
    {
        Config::load(array(
            'App' => array(
                'one' => 1,
                'two' => 2,
                'three' => 3,
            ),
            'Test' => 'test',
            'another' => new \stdClass()
        ));

        $expected = array(
            'App' => array(
                'one' => 1,
                'two' => 2,
                'three' => 3,
            ),
            'Test' => 'test',
            'another' => new \stdClass()
        );

        foreach ($expected as $key => $value) {
            $actual = Config::get($key);
            $this->assertEquals($value, $actual);
        }
    }

    /**
     * Test loading of a yaml file for configuration
     */
    public function testLoadYaml()
    {
        Config::loadYaml(__DIR__ . '/data/test_config.yml');

        $expected = array(
            'myValue' => 'oki doki',
            'anotherval' => array(
                'one' => 'ok',
                'two' => 'okok',
                'three' => 'okokok'
            )
        );

        foreach ($expected as $key => $value) {
            $actual = Config::get($key);
            $this->assertEquals($value, $actual);
        }
    }

    /**
     * Test that loading a yaml file which doesnt exist throws the correct exception.
     *
     * @throws ConfigException
     */
    public function testLoadYamlThrowsExceptionsOnMissingFile()
    {
        $this->setExpectedException('Gaw508\Config\Exception\ConfigException');
        Config::loadYaml(__DIR__ . '/data/test_config_non_existant.yml');
    }

    /**
     * Test that the load directory function loads all yaml files in directory
     */
    public function testLoadDirectory()
    {
        Config::loadDirectory(__DIR__ . '/data');

        $expected = array(
            'myValue' => 'oki doki',
            'anotherval' => array(
                'one' => 'ok',
                'two' => 'okok',
                'three' => 'okokok'
            ),
            'secondYamlFileConfig' => 'it works!'
        );

        $not_expected = array(
            'nonYamlConfigValue'
        );

        foreach ($expected as $key => $value) {
            $actual = Config::get($key);
            $this->assertEquals($value, $actual);
        }

        foreach ($not_expected as $key) {
            try {
                Config::get($key);
                $this->assertTrue(false);
            } catch (ConfigException $e) {
                $this->assertContains('Missing configuration value', $e->getMessage());
            }
        }
    }

    /**
     * Test that the clear all function clears out all config
     *
     * @throws ConfigException
     */
    public function testClearAll()
    {
        $this->setExpectedException('Gaw508\Config\Exception\ConfigException');

        Config::set('Test', 'test');
        $this->assertEquals('test', Config::get('Test'));
        Config::clearAll();
        Config::get('Test');
    }
}
