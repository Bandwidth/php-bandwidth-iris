<?php

namespace Iris;

class ServiceAddress {
    use BaseModel;

    protected $fields = array(
        "City" => array("type" => "string", "required" => true),
        "HouseNumber" => array("type" => "string", "required" => true),
        "StreetName" => array("type" => "string", "required" => true),
        "StateCode" => array("type" => "string", "required" => true),
        "Zip" => array("type" => "string"),
        "Country" => array("type" => "string")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}
