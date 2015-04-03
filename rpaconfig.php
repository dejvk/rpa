<?php
/**
 * RPA Configuration File.
 * Unless you know what you are doing, you should never edit anything except
 * this constants.
 * 
 * @package RPA\config
 * @version 1.0
 */

  class CFG {
    /** @var string Database hostname as URL or IP. */
    const DB_HOST = "";

    /** @var int Database port to connect. Defaults to 3306 for MySQL. */
    const DB_PORT = 3306;

    /** @var string Database user name. */
    const DB_USER = "";

    /** @var string Database user password. */
    const DB_PASS = "";
    
    /** @var string Database name, where tables are found. */
    const DB_NAME = "";
    
    /** @var string Event table name. */
    const DB_TBL_EVENT = "";
    
    /** @var string Event's name column name. */
    const DB_COL_EVENT_NAME  = "";

    /** @var string Event's description column name. */
    const DB_COL_EVENT_DESC  = "";

    /** @var string Event's start datetime column name. */
    const DB_COL_EVENT_START = "";
  }
