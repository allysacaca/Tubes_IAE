<?php

use CodeIgniter\Boot;
use Config\Paths;

/*
 *---------------------------------------------------------------
 * CHECK PHP VERSION
 *---------------------------------------------------------------
 */

$minPhpVersion = '8.1'; // If you update this, don't forget to update `spark`.
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    $message = sprintf(
        'Your PHP version must be %s or higher to run CodeIgniter. Current version: %s',
        $minPhpVersion,
        PHP_VERSION,
    );

    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo $message;

    exit(1);
}

/*
 *---------------------------------------------------------------
 * ENABLE ERROR DISPLAY FOR DEBUGGING
 *---------------------------------------------------------------
 */

// Enable error display for debugging (IMPORTANT FOR DEVELOPMENT)
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

/*
 *---------------------------------------------------------------
 * SET THE CURRENT DIRECTORY
 *---------------------------------------------------------------
 */

// Path to the front controller (this file)
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Ensure the current directory is pointing to the front controller's directory
if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

/*
 *---------------------------------------------------------------
 * BOOTSTRAP THE APPLICATION
 *---------------------------------------------------------------
 * This process sets up the path constants, loads and registers
 * our autoloader, along with Composer's, loads our constants
 * and fires up an environment-specific bootstrapping.
 */

// LOAD OUR PATHS CONFIG FILE
// This is the line that might need to be changed, depending on your folder structure.
$pathsConfig = FCPATH . '../app/Config/Paths.php';

// Check if Paths.php exists
if (!file_exists($pathsConfig)) {
    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo '<h1>Configuration Error</h1>';
    echo '<p>The file <strong>app/Config/Paths.php</strong> is missing.</p>';
    echo '<p>Expected path: ' . $pathsConfig . '</p>';
    exit(1);
}

require $pathsConfig;

$paths = new Paths();

// LOAD THE FRAMEWORK BOOTSTRAP FILE
$bootFile = $paths->systemDirectory . '/Boot.php';

// Check if Boot.php exists
if (!file_exists($bootFile)) {
    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo '<h1>Framework Error</h1>';
    echo '<p>The file <strong>system/Boot.php</strong> is missing.</p>';
    echo '<p>Expected path: ' . $bootFile . '</p>';
    echo '<p>Please run: <code>composer install</code></p>';
    exit(1);
}

require $bootFile;

exit(Boot::bootWeb($paths));