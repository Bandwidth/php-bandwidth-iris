<?php

namespace Iris;

use AllowDynamicProperties;

#[AllowDynamicProperties]
class ImportTnOrderResponse {
    use BaseModel;

    protected $fields = array(
        "TotalCount" => array("type" => "integer"),
        "ImportTnOrder" => array("type" => "\Iris\ImportTnOrder"),
        "ImportTnOrderSummary" => array("type" => "\Iris\ImportTnOrder"),
        "Location" => array("type" => "string")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}
