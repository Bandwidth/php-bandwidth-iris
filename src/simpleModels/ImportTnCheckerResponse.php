<?php

namespace Iris;

class ImportTnCheckerResponse {
    use BaseModel;

    protected $fields = array(
        "ImportTnCheckerPayload" => array("type" => "\Iris\ImportTnCheckerPayload"),
        "Errors" => array("type" => "\Iris\Error")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}
