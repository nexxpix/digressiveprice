<?php

namespace DigressivePrice\Event;

use Thelia\Core\Event\ActionEvent;

class DigressivePriceIdEvent extends ActionEvent {

    protected $id;

    function __construct($id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}