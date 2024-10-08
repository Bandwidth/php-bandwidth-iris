<?php

namespace Iris;

use AllowDynamicProperties;

#[AllowDynamicProperties]
class BdrCreationResponse {
    use BaseModel;

    protected $fields = array(
        "Info" => array("type" => "string"),
        "Location" => array("type" => "string"),
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}
