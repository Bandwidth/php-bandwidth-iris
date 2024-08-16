<?php

namespace Iris;

use AllowDynamicProperties;

#[AllowDynamicProperties]
class ImportTnCheckerPayload {
    use BaseModel;

    protected $fields = array(
        "TelephoneNumbers" => array("type" => "\Iris\Phones"),
        "ImportTnErrors" => array("type" => "\Iris\ImportTnErrors")
    );

    public function __construct($data) {
        $this->set_data($data, true);
    }
}
