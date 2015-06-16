<?php

namespace Iris;

class WirelessInfo {
    use BaseModel;

    protected $fields = array();

    public function __construct($data) {
        $this->set_data($data);
    }
}
