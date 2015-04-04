<?php

/**
 * RPA Library File.
 * Here lies the object model for RPA.
 * As this is small in first version, it lies in the single file. It is
 * intended to split the file when RPA grows.
 * 
 * @version 1.0
 *
 */

/**
 * Main renderer.
 * Dispatcher controls delivering appropriate output to given request.
 */
class Dispatcher {

    /** @var DatabaseConnection Universal Data Access Object */
    private $database;

    /**
     * Contacts database and saves DB link.
     * @throws PDOException Database is not reachable.
     */
    public function __construct() {
        $this->database = new DatabaseConnection();
    }

    /**
     * Definition of URL routes and corresponding content.
     * Uses GET parameters to define routes. 
     * Defaults to Dispatcher::renderEmptyRequest().
     */
    public function resolveContent() {
        switch ($_GET['get']) {
            case 'events':
                $this->renderEventsRequest($_GET['limit'], true);
                break;
            default:
                $this->renderEmptyRequest();
                break;
        }
    }

    /**
     * Renders events as JSON.
     * @param int $limit Max number of retrieved events.
     * @param bool $includerunning Determines whether already running events
     * should be included in output.
     */
    private function renderEventsRequest($limit, $includerunning) {
        $events = $this->database->getEvents($limit, $includerunning);
        header('Content-Type: application/json');
        foreach ($events as $event)
            echo json_encode($event->asArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Default output when no data are requested.
     */
    private function renderEmptyRequest() {
        header('Content-Type: text/plain');
        echo "No data requested.";
    }

}

/**
 * Universal Data Access Object.
 * Retrieves data from database and creates objects from it.
 */
class DatabaseConnection {

    /** @var PDO Database driver */
    private $conn;

    /**
     * Sets up connection.
     * @throws PDOException Configured database is not reachable.
     */
    public function __construct() {
        $host = CFG::DB_HOST;
        $port = CFG::DB_PORT;
        $user = CFG::DB_USER;
        $pass = CFG::DB_PASS;
        $dbname = CFG::DB_NAME;

        $dsn = "mysql:host={$host};port={$port};dbname={$dbname}";
        $this->conn = new PDO($dsn, $user, $pass);
        $this->conn->query("SET NAMES utf8");
        unset($user, $pass);
    }

    /**
     * Retrieves events from database and transfers them to objects.
     * @param int $limit Max number of retrieved events
     * @param bool $includerunning Determines wheter list should include
     * events that started less than 3 hours ago. Defaults to true.
     * @throws PDOException Database error.
     * @return Event[] List of oncoming events.
     */
    public function getEvents($limit, $includerunning = true) {
        $limit = htmlspecialchars($limit);
        $start = ($includerunning) ? "DATE_SUB(NOW(),INTERVAL 3 HOUR)" : "NOW()";
        $stmt = $this->conn->prepare("SELECT " . CFG::DB_COL_EVENT_NAME . " AS name, " . CFG::DB_COL_EVENT_DESC . " AS description, " . CFG::DB_COL_EVENT_START . " AS start
        FROM   " . CFG::DB_TBL_EVENT . "
        WHERE  " . CFG::DB_COL_EVENT_START . " >= {$start}
        ORDER BY " . CFG::DB_COL_EVENT_START . "
        LIMIT  0, {$limit};");
        if (!$stmt->execute())
            throw new PDOException();
        $result = $stmt->fetchAll();
        $events = array();
        foreach ($result as $row) {
            $event = new Event();
            $event->setName($row['name'])
                    ->setDesc($row['description'])
                    ->setStart($row['start']);
            $events[] = $event;
        }
        return $events;
    }

}

/**
 * Single planned event on server.
 */
class Event {

    /** @var string Event's name */
    private $name;
    /** @var string Event's description */
    private $desc;
    /** @var string Event's start date and time */
    private $start;

    /**
     * Sets event's name.
     * @param string $n Event name
     * @return self
     */
    public function setName($n) {
        $this->name = $n;
        return $this;
    }

    /**
     * Sets event's description.
     * @param string $d Event description
     * @return self
     */
    public function setDesc($d) {
        $this->desc = $d;
        return $this;
    }

    /**
     * Sets event's start date.
     * Date is accepted either as unix timestamp or as SQL datetime type.
     * @param string|int Event start date
     * @return self
     */
    public function setStart($s) {
        $this->start = (is_numeric($s)) ? date("Y-m-d H:i:s", $s) : $s;
        return $this;
    }

    /**
     * Serialize object to associative array.
     * @return string[] Serialized object.
     */
    public function asArray() {
        return array("name" => $this->name, "description" => $this->desc, "start" => $this->start);
    }

}
