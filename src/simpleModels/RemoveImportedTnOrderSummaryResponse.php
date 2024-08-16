<?php

namespace Iris;

use AllowDynamicProperties;

#[AllowDynamicProperties]
class RemoveImportedTnOrderSummaryResponse {
    use BaseModel;

    protected $fields = array(
        "TotalCount" => array("type" => "integer"),
        "RemoveImportedTnOrderSummary" => array("type" => "\Iris\RemoveImportedTnOrderSummary")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}
