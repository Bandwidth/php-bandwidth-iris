<?php

namespace Iris;

class EnrSms {
    use BaseModel;

    protected $fields = array(
        "TelephoneNumber" => array("type" => "string")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}
