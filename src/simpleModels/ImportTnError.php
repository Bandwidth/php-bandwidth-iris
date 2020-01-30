<?php

namespace Iris;

class ImportTnError {
    use BaseModel;

    protected $fields = array(
        "TelephoneNumbers" => array("type" => "\Iris\Phones"),
        "Code" => array("type" => "string"),
        "Description" => array("type" => "string")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}
