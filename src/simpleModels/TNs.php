<?php

namespace Iris;

class TNs {
    use BaseModel;

    protected $fields = array(
        "TotalCount" => array("type" => "integer"),
        "TelephoneNumbers" => array("type" => "\Iris\Phones"),
        "Links" => array("type" => "\Iris\Links")
    );

    public function __construct($data) {
        $this->set_data($data, true);
    }
}
