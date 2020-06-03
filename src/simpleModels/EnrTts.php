<?php

namespace Iris;

class EnrTts {
    use BaseModel;

    protected $fields = array(
        "TelephoneNumber" => array("type" => "string")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}
