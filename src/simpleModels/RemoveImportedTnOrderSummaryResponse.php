<?php

namespace Iris;

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
