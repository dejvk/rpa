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
 * @package RPA
 * @version 1.0
 */

  require_once ( 'rpaconfig.php' );
  require_once ( 'rpalib.php' );

  
  try {
    $dispatcher = new Dispatcher();
    $dispatcher -> resolveContent();
  }
  catch (PDOException $e) {
    header('Content-Type: text/plain');
    echo "Data source unavailable: " . $e;
  }
  
