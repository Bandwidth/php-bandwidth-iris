<?php

namespace Iris;

class OrderHistory {
    use BaseModel;

    protected $fields = array(
        "OrderDate" => array("type" => "string"),
        "Note" => array("type" => "string"),
        "Author" => array("type" => "string"),
        "Status" => array("type" => "string")
    );

    public function __construct($data) {
        $this->set_data($data, true);
    }
}
