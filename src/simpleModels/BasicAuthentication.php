<?php

namespace Iris;

use AllowDynamicProperties;

#[AllowDynamicProperties]
class BasicAuthentication {
    use BaseModel;

    protected $fields = array(
        "Username" => array("type" => "string"),
        "Password" => array("type" => "string"),
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}
