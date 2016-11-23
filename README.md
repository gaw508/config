Config - PHP Application Config Library
=======================================

[![Build Status](https://travis-ci.org/gaw508/config.svg?branch=master)](https://travis-ci.org/gaw508/config)

A library to manage config key value pairs for PHP applications.
Configuration can be loaded from YAML files.

### Installation ###

The latest version can be installed with composer:

    composer require gaw508/config

### Basic Usage ###

    <?php
    
    require_once 'path/to/vendor/autoload.php';
    
    use Gaw508\Config;
    
    // Load a single YAML config file
    Config::loadYaml('config/defaults.yml');
    
    // Load a directory of YAML config files
    Config::loadDirectory('config/autoload');
    
    // Use a config value from YAML file
    echo Config::get('some_val_from_loaded_file');
    // Output: `foo bar`
    
    // Set a single config value
    Config::set('my_val', 12);
    
