<?php

namespace Iris;

use AllowDynamicProperties;

#[AllowDynamicProperties]
class ImportTnErrors {
    use BaseModel;

    protected $fields = array(
        "ImportTnError" => array("type" => "\Iris\ImportTnError")
    );

    public function __construct($data) {
        $this->set_data($data, true);
    }
}
