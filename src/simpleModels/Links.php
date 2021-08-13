<?php

namespace Iris;

class Links {
    use BaseModel;

    protected $fields = array(
        "first" => array("type" => "string"),
        "next" => array("type" => "string"),
        "last" => array("type" => "string")
    );

    public function __construct($data) {
        $this->set_data($data, true);
    }
}
