<?php

namespace rpa;

/**
 * Universal Data Access Object.
 * Retrieves data from database and creates objects from it.
 */
class DatabaseConnection {

    /** @var \PDO Database driver */
    private $conn;

    /**
     * Sets up connection.
     * @throws \PDOException Configured database is not reachable.
     */
    public function __construct() {
        $host = CFG::DB_HOST;
        $port = CFG::DB_PORT;
        $user = CFG::DB_USER;
        $pass = CFG::DB_PASS;
        $dbname = CFG::DB_NAME;

        $dsn = "mysql:host={$host};port={$port};dbname={$dbname}";
        $this->conn = new \PDO($dsn, $user, $pass);
        $this->conn->query("SET NAMES utf8");
        unset($user, $pass);
    }

    /**
     * Retrieves events from database and transfers them to objects.
     * @param bool $includerunning Determines wheter list should include
     * events that started less than 3 hours ago. Defaults to true.
     * @throws \PDOException Database error.
     * @return Event[] List of oncoming events.
     */
    public function getEvents($includerunning = true) {
        $start = ($includerunning) ? "DATE_SUB(NOW(),INTERVAL 3 HOUR)" : "NOW()";
        $stmt = $this->conn->prepare("SELECT " . CFG::DB_COL_EVENT_NAME . " AS name, " . CFG::DB_COL_EVENT_DESC . " AS description, " . CFG::DB_COL_EVENT_START . " AS start
        FROM   " . CFG::DB_TBL_EVENT . "
        WHERE  " . CFG::DB_COL_EVENT_START . " >= {$start}
        ORDER BY " . CFG::DB_COL_EVENT_START . "
        LIMIT  0, 25;");
        if (!$stmt->execute()) {
            throw new \Exception($stmt->errorInfo()[2]);
        }
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