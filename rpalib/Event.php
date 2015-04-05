<?php

namespace rpa;

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