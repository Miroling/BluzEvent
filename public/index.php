<?php
/**
 * Index file
 *
 * @author   C.O.
 * @created  06.07.11 16:20
 */
// Environment
define('ENVIRONMENT_PRODUCTION', 'production');
define('ENVIRONMENT_DEVELOPMENT', 'development');
define('ENVIRONMENT_TESTING', 'testing');
define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : ENVIRONMENT_PRODUCTION));

// Debug mode for development environment only
if (isset($_COOKIE['BLUZ_DEBUG'])) {
    define('DEBUG', true);
    error_reporting(E_ALL | E_STRICT);
    ini_set('display_errors', 1);
} else {
    define('DEBUG', false);
    error_reporting(0);
    ini_set('display_errors', 0);
}


// Paths
define('PATH_ROOT', realpath(dirname(__FILE__) . '/../'));
define('PATH_APPLICATION', PATH_ROOT . '/application');
define('PATH_DATA', PATH_ROOT . '/data');
define('PATH_LIBRARY', PATH_ROOT . '/library');
define('PATH_PUBLIC', PATH_ROOT . '/public');
define('PATH_THEME', PATH_ROOT . '/themes');

// Shutdown function for handle critical and other errors
register_shutdown_function('errorHandler');

// iframe header - prevent security issues
header('X-Frame-Options: SAMEORIGIN');

function errorHandler() {
    $e = error_get_last();
    if (!is_array($e)
        || !in_array($e['type'], array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR))) {
        return;
    }
    require_once 'error.php';
}

// Try to run application
try {
    // init loader
    require_once PATH_LIBRARY . '/Bluz/_loader.php';
    require_once PATH_APPLICATION . '/Bootstrap.php';
    require_once PATH_APPLICATION . '/Exception.php';

    /**
     * @var \Application\Bootstrap $app
     */
    $app = \Application\Bootstrap::getInstance();
    $app->init(APPLICATION_ENV)
        ->process()
        ->render();
} catch (Exception $e) {
    require_once 'error.php';
}
