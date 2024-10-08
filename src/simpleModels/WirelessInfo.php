<?php

namespace Iris;

use AllowDynamicProperties;

#[AllowDynamicProperties]
class WirelessInfo {
    use BaseModel;

    protected $fields = array(
        "AccountNumber" => array("type" => "string"),
        "PinNumber" => array("type" => "string")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}
