<?php

/**
 * RPA - Roleplaying.cz API
 * Universal API for standardized export of data from
 * different game server databases.
 *
 * This is main run file. See configuration file for settings or library file
 * for more technical details.
 *
 * If you feel you can improve RPA, either post an issue on github or fork
 * repo and do what you can.
 *
 * @version 1.0
 */
/** Loads required configuration file */
require_once ( 'rpaconfig.php' );

/** Autoloading classes */
function __autoload($className) {
    $dirs = array('rpalib');
    foreach ($dirs as $dir) {
        if (file_exists($dir . '/' . $className . '.php')) {
            include_once $dir . '/' . $className . '.php';
        }
    }
}

try {
    $dispatcher = new Dispatcher();
    $dispatcher->resolveContent();
} catch (PDOException $e) {
    header('Content-Type: text/plain');
    echo "Data source unavailable: " . $e;
}
  
