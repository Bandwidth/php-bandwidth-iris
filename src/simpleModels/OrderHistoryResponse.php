<?php

namespace Iris;

use AllowDynamicProperties;

#[AllowDynamicProperties]
class OrderHistoryResponse {
    use BaseModel;

    protected $fields = array(
        "OrderHistory" => array("type" => "\Iris\OrderHistory"),
    );

    public function __construct($data) {
        $this->set_data($data, true);
    }
}
