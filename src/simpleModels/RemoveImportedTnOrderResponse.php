<?php

namespace Iris;

class RemoveImportedTnOrderResponse {
    use BaseModel;

    protected $fields = array(
        "RemoveImportedTnOrder" => array("type" => "\Iris\RemoveImportedTnOrder"),
        "Errors" => array("type" => "\Iris\Error"),
        "ProcessingStatus" => array("type" => "string")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}
