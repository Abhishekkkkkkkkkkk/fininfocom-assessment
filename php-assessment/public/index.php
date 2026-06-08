<?php

// Check PHP version.
if (version_compare(PHP_VERSION, '8.1', '<')) {
    echo 'Your PHP version must be 8.1 or higher to run CodeIgniter 4. Current version: ' . PHP_VERSION . "\n";
    exit(1);
}

// Path to the front controller (this file)
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Ensure the current directory is pointing to the front controller's directory
if (PHP_SAPI !== 'cli') {
    chdir(__DIR__);
}

/*
 *---------------------------------------------------------------
 * BOOTSTRAP THE FRAMEWORK
 *---------------------------------------------------------------
 * This bootstrap file loads and initializes the class autoloader,
 * core configurations, and helper functions.
 */

// Load our paths config file
require FCPATH . '../app/Config/Paths.php';
$paths = new Config\Paths();

// Load the framework bootstrap
require $paths->systemDirectory . '/Boot.php';

// Launch the application
CodeIgniter\Boot::bootWeb($paths);
