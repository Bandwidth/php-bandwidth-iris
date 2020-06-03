<?php

namespace Iris;

class EnrCallback {
    use BaseModel;

    protected $fields = array(
        "Url" => array("type" => "string"),
        "Credentials" => array("type" => "\Iris\Credentials")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}
