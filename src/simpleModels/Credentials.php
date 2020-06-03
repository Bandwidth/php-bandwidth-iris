<?php

namespace Iris;

class Credentials {
    use BaseModel;

    protected $fields = array(
        "Username" => array("type" => "string")
    )

    public function __construct($data) {
        $this->set_data($data);
    }
}
