<?php

namespace Iris;

use AllowDynamicProperties;

#[AllowDynamicProperties]
class Bdr {
    use BaseModel;

    protected $fields = array(
        "StartDate" => array("type" => "string"),
        "EndDate" => array("type" => "string"),
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}
