<?php

namespace Config;

use CodeIgniter\Config\AutoloadConfig;

/**
 * Autoload Configuration
 *
 * Register namespaces and class maps.
 */
class Autoload extends AutoloadConfig
{
    /**
     * Array of namespaces mapping to directories.
     */
    public $psr4 = [
        APP_NAMESPACE => APPPATH, // For custom app namespace (App)
        'Config'      => APPPATH . 'Config',
    ];

    /**
     * Array of class maps.
     */
    public $classmap = [];
}
