<?php

namespace Iris;

class ImportTnOrderResponse {
    use BaseModel;

    protected $fields = array(
        "ImportTnOrder" => array("type" => "\Iris\ImportTnOrder")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
)
