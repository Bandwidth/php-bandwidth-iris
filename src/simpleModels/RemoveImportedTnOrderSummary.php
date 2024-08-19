<?php

namespace Iris;

use AllowDynamicProperties;

#[AllowDynamicProperties]
class RemoveImportedTnOrderSummary {
    use BaseModel;

    protected $fields = array(
        "accountId" => array("type" => "string"),
        "CountOfTNs" => array("type" => "integer"),
        "CustomerOrderId" => array("type" => "string"),
        "userId" => array("type" => "string"),
        "lastModifiedDate" => array("type" => "string"),
        "OrderDate" => array("type" => "string"),
        "OrderType" => array("type" => "string"),
        "OrderStatus" => array("type" => "string"),
        "OrderId" => array("type" => "string")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}
