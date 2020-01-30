<?php

namespace Iris;

class ImportTnCheckerPayload {
    use BaseModel;

    protected $fields = array(
        "TelephoneNumbers" => array("type" => "\Iris\Phones"),
        "ImportTnErrors" => array("type" => "\Iris\ImportTnError")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}
