<?php

namespace rpa;

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
/** Loads required model library */
require_once ( 'rpalib/Dispatcher.php' );
require_once ( 'rpalib/DatabaseConnection.php' );
require_once ( 'rpalib/Event.php' );


try {
    $dispatcher = new Dispatcher();
    $dispatcher->resolveContent();
} catch (PDOException $e) {
    header('Content-Type: text/plain');
    echo "Data source unavailable: " . $e;
}
  
