<?php

namespace rpa;

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
     */
    public function resolveContent() {
        $request = filter_input(INPUT_GET, "get", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        switch ($request) {
            case 'events':
                $this->renderEventsRequest(true);
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
    private function renderEventsRequest($includerunning) {
        $events = $this->database->getEvents($includerunning);
        header('Content-Type: application/json');
        foreach ($events as $event) {
            echo json_encode($event->asArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Default output when no data are requested.
     */
    private function renderEmptyRequest() {
        header('Content-Type: text/plain');
        echo "No data requested. RPA 1.0.";
    }

}