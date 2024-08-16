<?php

namespace Iris;

use AllowDynamicProperties;

#[AllowDynamicProperties]
class RemoveImportedTnOrderResponse {
    use BaseModel;

    protected $fields = array(
        "RemoveImportedTnOrder" => array("type" => "\Iris\RemoveImportedTnOrder"),
        "Location" => array("type" => "string")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}
