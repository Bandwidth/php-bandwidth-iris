<?php

namespace Iris;

use AllowDynamicProperties;

#[AllowDynamicProperties]
class EmailSubscription {
    use BaseModel;

    protected $fields = array(
        "Email" => array("type" => "string"),
        "DigestRequested" => array("type" => "string"),
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}
