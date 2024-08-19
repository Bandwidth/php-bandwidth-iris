<?php

namespace Iris;

use AllowDynamicProperties;

#[AllowDynamicProperties]
class InserviceTns {
    use BaseModel;

    protected $fields = array(
        "TotalCount" => array("type" => "string"),
        "TelephoneNumbers" => array("type" => "\Iris\Phones"),
        "Links" => array("type" => "\Iris\Links")
    );

    public function __construct($data) {
        $this->set_data($data, true);
    }
}
